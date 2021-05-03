<?php
declare(strict_types=1);

namespace App\Controller;


use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Exception\NotFoundException;


/**
 * MGenres Controller
 *
 * @property \App\Model\Table\MGenresTable $MGenres
 * @method \App\Model\Entity\MGenre[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MGenresController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // 検索データ取得
        $inputName = $this->request->getQuery('search_genres');
        // ジャンル名曖昧検索
        if(!empty($inputName)){
                $sqlName = $inputName;
            } else {
                $sqlName = '';
            };
        $mGenres = $this->paginate($this->MGenres->find()->where(['del_flg'=> 0,'genre LIKE' => "%{$sqlName}%"]));

        $this->set(compact('mGenres'));
    }

    /**
     * View method
     *
     * @param string|null $id M Genre id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    // public function view($id = null)
    // {
    //     $mGenre = $this->MGenres->get($id, [
    //         'contain' => [],
    //     ]);

    //     $this->set(compact('mGenre'));
    // }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->userValid();

        $mGenre = $this->MGenres->newEmptyEntity();
        if ($this->request->is('post')) {
            // トランザクション
            $connection = ConnectionManager::get('default');
            $connection->begin();
            try {
            $data = $this->request->getData();
            $data['del_flg'] = 0;
            $mGenre = $this->MGenres->patchEntity($mGenre, $data);
                if($this->MGenres->save($mGenre)) {
               //コミット
               $connection->commit();
                $this->Flash->success(__('ジャンル登録に成功しました。'));
                }
            } catch (\Exception $e) {
             // ロールバック
               $connection->rollback();
                return $this->redirect(['action' => 'index']);
               $this->Flash->error(__('ジャンル登録に失敗しました。'));
           }
        $this->set(compact('mGenre'));
    }
        $this->Flash->error(__('アクセスエラーが発生しました'));
        return $this->redirect(['action' => 'index']);
}

    /**
     * Edit method
     *
     * @param string|null $id M Genre id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    // public function edit($id = null)
    // {
    //     $mGenre = $this->MGenres->get($id, [
    //         'contain' => [],
    //     ]);
    //     if ($this->request->is(['patch', 'post', 'put'])) {
    //         $mGenre = $this->MGenres->patchEntity($mGenre, $this->request->getData());
    //         if ($this->MGenres->save($mGenre)) {
    //             $this->Flash->success(__('The m genre has been saved.'));

    //             return $this->redirect(['action' => 'index']);
    //         }
    //         $this->Flash->error(__('The m genre could not be saved. Please, try again.'));
    //     }
    //     $this->set(compact('mGenre'));
    // }

    /**
     * Delete method
     *
     * @param string|null $id M Genre id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->userValid();

        $mGenre = $this->MGenres->get($id);
        if ($this->request->allowMethod(['post', 'delete'])) {
           // トランザクション
        $connection = ConnectionManager::get('default');
        $connection->begin();
        try {
            $mGenre = $this->MGenres->patchEntity(
                $mGenre,
                ['del_flg' => 1]
            );
            if ($this->MGenres->save($mGenre)) {
                      //   コミット
                $connection->commit();
                $this->Flash->success(__('ジャンルの削除に成功しました。'));
            }
        } catch (\Exception $e) {
            // ロールバック
            $connection->rollback();
                $this->Flash->error(__('ジャンルの削除に失敗しました。'));
            }
        }
        $this->Flash->error(__('アクセスエラーが発生しました'));
        return $this->redirect(['action' => 'index']);
    }
        /* -------------------------------------------------------------------------- */
    /*ANCHOR                                  カスタムメソッド                                  */
    /* -------------------------------------------------------------------------- */

    /**
     * ユーザー権限によるリダイレクト処理
     *
     * @return void
     */
    protected function userValid()
    {
        if ($this->Session->read('User.role') != 3) {
            $this->Flash->error(__('管理者権限がありません'));
            return $this->redirect(['controller' => 'TBooks', 'action' => 'index']);
        }
    }
    /**
     * バリデーションエラーflashメソッド
     *
     * @param [type] $errors
     * @param [type] $action
     * @return void
     */
    protected function _flashError($errors = null, $action = null, $id = null)
    {
        // バリデーションエラー発生時、エラー表示＆リダイレクト
        foreach ($errors as $error => $value) {
            foreach ($value as $single_error) {
                $this->Flash->error(__($single_error));
            }
        }
        return $this->redirect(['action' => $action, $id]);
    }
}