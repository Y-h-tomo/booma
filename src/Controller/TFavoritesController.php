<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Exception\NotFoundException;

/**
 * TFavorites Controller
 *
 * @property \App\Model\Table\TFavoritesTable $TFavorites
 * @method \App\Model\Entity\TFavorite[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TFavoritesController extends AppController
{


    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $user_id = $this->Session->read('User.id');
        $data = $this->request->getQuery('search_books');
        $this->paginate = [
            'sortableFields' => [
                'id', 'TBooks.name', 'TBooks.book_no', 'TBooks.remain', 'TBooks.quantity', 'TBooks.id', 'TBooks.image'
            ],
            'contain' => ['TBooks', 'MUsers'],
        ];
        if (!empty($data)) {
            $tFavorites = $this->paginate($this->TFavorites->find()->where(['TBooks.name LIKE' => "%{$data}%", 'TFavorites.m_users_id' => $user_id]));
        } else {
            $tFavorites = $this->paginate($this->TFavorites->find()->where(['TFavorites.m_users_id' => $user_id]));
        }

        $genres = $this->TFavorites->TBooks->MGenres->find();

        $this->set(compact('genres'));

        $this->set(compact('tFavorites'));
    }



    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $tFavorite = $this->TFavorites->newEmptyEntity();
        if ($this->request->is('post')) {
            // トランザクション
            $connection = ConnectionManager::get('default');
            $connection->begin();
            try {
                // del_flg=0で新規登録
                $data = $this->request->getData();
                $data['del_flg'] = 0;
                $tFavorite = $this->TFavorites->patchEntity($tFavorite, $data);
                if ($this->TFavorites->save($tFavorite)) {

                    //   コミット
                    $connection->commit();
                    echo $this->request->isCheck('ajax');
                    exit;
                }
            } catch (\Exception $e) {
                // ロールバック
                $connection->rollback();
                echo $this->request->isCheck('ajax');
                exit;
            }
        }
    }
    /**
     * Delete method
     *
     * @param string|null $id T Favorite id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete()
    {
        if ($this->request->is('post')) {

            // データ取得
            $data = $this->request->getData();
            // 取得データより、最新の登録データを検索
            $checked = $this->TFavorites->find()->where(['TFavorites.del_flg' => 0, 'TFavorites.m_users_id' => $data['m_users_id'], 'TFavorites.t_books_id' => $data['t_books_id']])->orderDesc('id')->first();

            // トランザクション
            $connection = ConnectionManager::get('default');
            $connection->begin();
            try {
                // 登録データよりID取得
                $tFavorite = $this->TFavorites->get($checked['id']);
                // 論理削除フラグ　del_flg = 1で更新
                $data['del_flg'] = 1;
                $tFavorite = $this->TFavorites->patchEntity($tFavorite, $data);
                if ($this->TFavorites->save($tFavorite)) {

                    //   コミット
                    $connection->commit();
                    echo $this->request->isCheck('ajax');
                    exit;
                }
            } catch (\Exception $e) {
                // ロールバック
                $connection->rollback();
                echo $this->request->isCheck('ajax');
                exit;
            }
        }
    }
}