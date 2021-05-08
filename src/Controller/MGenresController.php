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
        $csv = $this->request->getQuery('csv');
        // ジャンル名曖昧検索
        if (!empty($inputName)) {
            $sqlName = $inputName;
        } else {
            $sqlName = '';
        };
        $mGenres = $this->paginate($this->MGenres->find()->where(['genre LIKE' => "%{$sqlName}%"]));


        /* -------------------------------- csv出力メソッド ------------------------------- */
        if ($csv == 1) {
            $this->_csvGenresExport($mGenres);
        }

        $this->set(compact('mGenres'));
    }

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
                if ($this->MGenres->save($mGenre)) {
                    //コミット
                    $connection->commit();
                    $this->Flash->success(__('ジャンル登録に成功しました。'));
                    return $this->redirect(['action' => 'index']);
                }
            } catch (\Exception $e) {
                // ロールバック
                $connection->rollback();
                $this->Flash->error(__('ジャンル登録に失敗しました。'));
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('mGenre'));
    }

    /**
     * Edit method
     *
     * @param string|null $id M Genre id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
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
                    ['del_flg' => 0]
                );
                if ($this->MGenres->save($mGenre)) {
                    //   コミット
                    $connection->commit();
                    $this->Flash->success(__('ジャンルをONしました。'));
                    return $this->redirect(['action' => 'index']);
                }
            } catch (\Exception $e) {
                // ロールバック
                $connection->rollback();
                $this->Flash->error(__('ジャンルのONに失敗しました。'));
                return $this->redirect(['action' => 'index']);
            }
        }
    }

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

        if ($this->request->is('get')) {
            // GETアクセスだった場合の処理（→500とか出せばいい感じ？）
            throw new InternalErrorException('投稿の削除に失敗しました');
        }

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
                    $this->Flash->success(__('ジャンルをOFFしました。'));
                    return $this->redirect(['action' => 'index']);
                }
            } catch (\Exception $e) {
                // ロールバック
                $connection->rollback();
                $this->Flash->error(__('ジャンルのONに失敗しました。'));
                return $this->redirect(['action' => 'index']);
            }
        }
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

    /**
     * csv出力メソッド
     */
    protected function _csvGenresExport($mGenres)
    {
        $genres = $mGenres->toArray();
        $csvGenres = [];
        foreach ($genres as $genre) {
            $genre['del_flg'] = $genre['del_flg'] ? "OFF" : "ON";
            $csvGenres[] = [
                $genre['id'],
                $genre['genre'],
                $genre['del_flg']
            ];
        };
        $data =  mb_convert_encoding($csvGenres, 'SJIS-win', 'UTF-8');
        $_serialize = ['data'];
        $_header = ['登録ID', 'ジャンル名', 'active'];
        $_header =  mb_convert_encoding($_header, 'SJIS-win', 'UTF-8');
        $_csvEncoding = 'CP932';
        $_newline = "\r\n";
        $_eol = "\r\n";
        $this->setResponse($this->getResponse()->withDownload('Genres.csv'));
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('data', '_serialize', '_header', '_csvEncoding', '_newline', '_eol'));
    }
}
