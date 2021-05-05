<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Mailer\Mailer;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Exception\NotFoundException;
use App\Controller\TransportFactory;

/**
 * THistories Controller
 *
 * @property \App\Model\Table\THistoriesTable $THistories
 * @method \App\Model\Entity\THistory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class THistoriesController extends AppController
{

    /* -------------------------------------------------------------------------- */
    /* ANCHOR                                 レンタル履歴一覧                                  */
    /* -------------------------------------------------------------------------- */

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $user_id = $this->Session->read('User.id');
            // 検索データ取得
            $inputName = $this->request->getQuery('search_books');
            $inputGenre = $this->request->getQuery('genre');
            $csv = $this->request->getQuery('csv');

        $this->paginate = [
            'sortableFields' => [
                'id', 'TBooks.name', 'TBooks.book_no', 'rental_time', 'TBooks.remain', 'TBooks.quantity', 'TBooks.id', 'TBooks.image'
            ],
            'contain' => ['TBooks', 'MUsers','TBooks.MGenres'],
        ];
                // 書籍名曖昧検索
                if(!empty($inputName)){
                    $sqlName = $inputName;
                } else {
                    $sqlName = '';
                }

        // ジャンル選択によりフィルタリング
        if(!empty($inputGenre)){
            $id = explode(':',$inputGenre)[0];
            $tHistories = $this->paginate($this->THistories->find('all')->where(['TBooks.del_flg' => 0,'TBooks.name LIKE' => "%{$sqlName}%",'THistories.m_users_id' => $user_id])->contain(['TBooks.MGenres'])->matching('TBooks.MGenres', function ($q) use ($id) {return $q->where(['MGenres.id' => $id]);
            }));
        }else{
            $tHistories = $this->paginate($this->THistories->find('all')->where(['TBooks.del_flg' => 0,'TBooks.name LIKE' => "%{$sqlName}%",'THistories.m_users_id' => $user_id ]));
        }

        /* -------------------------------- csv出力メソッド ------------------------------- */
        if($csv == 1){
            $this->_csvIndexExport($tHistories);
        }


        $genres = $this->THistories->TBooks->MGenres->find();

        $this->set(compact('genres'));

        $this->set(compact('tHistories'));
    }

    /* -------------------------------------------------------------------------- */
    /*ANCHOR                                  レンタルマイページ                                 */
    /* -------------------------------------------------------------------------- */

    /**
     * View method
     *
     * @param string|null $id T History id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view()
    {
        $user_id = $this->Session->read('User.id');
                    // 検索データ取得
                    $inputName = $this->request->getQuery('search_books');
                    $inputGenre = $this->request->getQuery('genre');
                    $csv = $this->request->getQuery('csv');
        $this->paginate = [
            'sortableFields' => [
                'id', 'TBooks.name', 'TBooks.book_no', 'return_time', 'TBooks.remain', 'TBooks.quantity', 'TBooks.id', 'TBooks.image'
            ],
            'contain' => ['TBooks', 'MUsers','TBooks.MGenres']
        ];
            // 書籍名曖昧検索
            if(!empty($inputName)){
                $sqlName = $inputName;
            } else {
                $sqlName = '';
            }

        // ジャンル選択によりフィルタリング
        if(!empty($inputGenre)){
            $id = explode(':',$inputGenre)[0];
            $tHistories = $this->paginate($this->THistories->find('all')->where(['THistories.del_flg'=> 0,'TBooks.del_flg' => 0,'TBooks.name LIKE' => "%{$sqlName}%",'THistories.m_users_id' => $user_id])->contain(['TBooks.MGenres'])->matching('TBooks.MGenres', function ($q) use ($id) {return $q->where(['MGenres.id' => $id]);
            }));
        }else{
            $tHistories = $this->paginate($this->THistories->find('all')->where(['THistories.del_flg'=> 0,'TBooks.del_flg' => 0,'TBooks.name LIKE' => "%{$sqlName}%",'THistories.m_users_id' => $user_id ]));
        }

        $genres = $this->THistories->TBooks->MGenres->find();

        $this->set(compact('genres'));

        $this->set(compact('tHistories'));
    }

    /* -------------------------------------------------------------------------- */
    /*ANCHOR                                   レンタルメソッド                                   */
    /* -------------------------------------------------------------------------- */

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->request->allowMethod('post')) {

            // ユーザーID取得
            $user_id = $this->Session->read('User.id');

            // トランザクション
            $connection = ConnectionManager::get('default');
            $connection->begin();
            try {

                /* ---------------------------------- データ取得 --------------------------------- */
                $data = $this->request->getData();

                // 最新のレンタル情報
                $returned = $this->THistories->find()->where(['t_books_id' => $data['t_books_id'], 'm_users_id' => $data['m_users_id']])->orderDesc('id')->first();

                /* ---------------------------- レンタル状況によるリダイレクト処理 --------------------------- */

                if ($returned && $returned['del_flg'] == 0) {
                    $this->Flash->error(__('既にレンタルしています。'));
                    return $this->redirect(['controller' => 'TBooks', 'action' => 'index']);
                }
                /* --------------------------------- 書籍情報の取得 -------------------------------- */
                $tBook = $this->THistories->TBooks->get([$data['t_books_id']]);

                /* ---------------------------- 書籍在庫状況によるリダイレクト処理 --------------------------- */

                if ($tBook['remain'] === 0) {
                    $this->Flash->error(__('書籍の在庫数が不足しています。'));
                    return $this->redirect(['controller' => 'TBooks', 'action' => 'index']);
                }

                /* ---------------------------------- 初期値設定 ---------------------------------- */
                $time = FrozenTime::now();
                $data['del_flg'] = 0;
                $data['arrears'] = 0;
                $data['rental_time'] = $time;
                $data['return_time'] = $time->modify("+{$tBook['deadline']} hours");

                /* ---------------------------------- Entity --------------------------------- */

                $tHistory = $this->THistories->newEntity($data);

                if (!empty($tHistory->getErrors())) {
                    $this->_flashError($tHistory->getErrors());
                }

                /* ---------------------------------- save分岐 ---------------------------------- */

                if ($this->THistories->save($tHistory)) {

                    /* -------------------------------- 書籍の在庫数更新 -------------------------------- */
                    $remain = $tBook['remain'] - 1;
                    $tBook = $this->THistories->TBooks->patchEntity(
                        $tBook,
                        ['remain' => $remain]
                    );
                    $this->THistories->TBooks->save($tBook);

                    //   コミット
                    $connection->commit();
                    $this->Flash->success(__('レンタルに成功しました。'));
                    return $this->redirect(['action' => 'view']);
                }
            } catch (\Exception $e) {

                // ロールバック
                $connection->rollback();
                $this->Flash->error(__('レンタル処理に失敗しました。'));
                return $this->redirect(['controller' => 'TBooks', 'action' => 'index']);
            }
        }
        return $this->redirect(['controller' => 'TBooks', 'action' => 'index']);
    }


    /**
     * Edit method
     *
     * @param string|null $id T History id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    // public function edit($id = null)
    // {
    //     $tHistory = $this->THistories->get($id, [
    //         'contain' => [],
    //     ]);
    //     if ($this->request->is(['patch', 'post', 'put'])) {
    //         $tHistory = $this->THistories->patchEntity($tHistory, $this->request->getData());
    //         if ($this->THistories->save($tHistory)) {
    //             $this->Flash->success(__('The t history has been saved.'));

    //             return $this->redirect(['action' => 'index']);
    //         }
    //         $this->Flash->error(__('The t history could not be saved. Please, try again.'));
    //     }
    //     $tBooks = $this->THistories->TBooks->find('list', ['limit' => 200]);
    //     $mGenres = $this->THistories->MUsers->find('list', ['limit' => 200]);
    //     $this->set(compact('tHistory', 'tBooks', 'mGenres'));
    // }

    /* -------------------------------------------------------------------------- */
    /*ANCHOR                                     返却メソッド                                     */
    /* -------------------------------------------------------------------------- */

    /**
     * Delete method
     *
     * @param string|null $id T History id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete()
    {
        // $data = $this->request->getData();
        if ($this->request->allowMethod(['post', 'delete'])) {
            // トランザクション
            $connection = ConnectionManager::get('default');
            $connection->begin();
            try {

                /* ---------------------------------- データ取得 --------------------------------- */

                $data = $this->request->getData();

                // 最新のレンタル状況
                $returned = $this->THistories->find()->where(['t_books_id' => $data['t_books_id'], 'm_users_id' => $data['m_users_id']])->orderDesc('id')->first();

                /* ---------------------------- レンタル状況によるリダイレクト処理 --------------------------- */
                if ($returned && $returned['del_flg'] == 1) {
                    $this->Flash->error(__('既に返却しています。'));
                    return $this->redirect(['controller' => 'TBooks', 'action' => 'index']);
                }

                /* --------------------------------- 延滞時間の算出 -------------------------------- */
                $time = FrozenTime::now()->i18nFormat('yyyy-MM-dd HH:mm:ss');
                $returnTime = $returned->return_time->i18nFormat('yyyy-MM-dd HH:mm:ss');
                $arrears = floor((strtotime($time) - strtotime($returnTime))/HOUR);

                /* --------------------------------- 書籍情報の取得 -------------------------------- */
                $tBook = $this->THistories->TBooks->get([$data['t_books_id']]);

                /* ---------------------------- 書籍在庫状況によるリダイレクト処理 --------------------------- */

                if ($tBook['remain'] == $tBook['quantity']) {
                    $this->Flash->error(__('書籍の登録冊数を超えています'));
                    return $this->redirect(['controller' => 'TBooks', 'action' => 'index']);
                }

                /* ---------------------------------- データ更新 --------------------------------- */
                $tHistory = $this->THistories->get($returned['id']);
                if($arrears > 0){
                    $tHistory['arrears'] = $arrears;
                }
                $tHistory  = $this->THistories->patchEntity(
                    $tHistory,
                    ['del_flg' => 1]
                );

                if (!empty($tHistory->getErrors())) {
                    $this->_flashError($tHistory->getErrors());
                }
                /* ---------------------------------- 分岐処理 ---------------------------------- */

                if ($this->THistories->save($tHistory)) {

                    /* -------------------------------- 書籍の在庫数更新 -------------------------------- */
                    $remain = $tBook['remain'] + 1;
                    $tBook = $this->THistories->TBooks->patchEntity(
                        $tBook,
                        ['remain' => $remain]
                    );
                    $this->THistories->TBooks->save($tBook);



                    //   コミット
                    $connection->commit();
                    $this->Flash->success(__('書籍の返却に成功しました。'));
                    if ($arrears > 0) {
                        $this->Flash->warning(__("延滞時間が{$arrears}時間 発生しました。"));
                    }
                    return $this->redirect(['controller' => 'TBooks', 'action' => 'index']);
                }
            } catch (\Exception $e) {

                // ロールバック
                $connection->rollback();
                $this->Flash->error(__('書籍の返却に失敗しました。'));
                return $this->redirect(['controller' => 'TBooks', 'action' => 'index']);
            }
        }
        return $this->redirect(['controller' => 'TBooks', 'action' => 'index']);
    }


/* -------------------------------------------------------------------------- */
/*                                  メール送信メソッド                                 */
/* -------------------------------------------------------------------------- */
public function sendMail()
{
    $time = FrozenTime::now()->modify("+24 hours");
    $tHistories = $this->THistories->find()->contain(['MUsers','TBooks'])->where(['TBooks.del_flg' => 0,'MUsers.del_flg'=> 0,'THistories.del_flg' => 0,'THistories.return_time <' => $time])->toArray();

    // debug($tHistories);
    // exit;
    foreach($tHistories as $history){
        $mailer = new Mailer();
        $mailer->setFrom(['me@example.com' => 'Booma'])
            ->setDomain('www.example.org')
            ->setTo($history->m_user['email'])
            ->setSubject("Booma: {$history->t_book['name']}の返却期限のご連絡")
            ->viewBuilder()
                ->setTemplate('default')
                ->setVar('message', 'こんにちは、世界！');
        $mailer->deliver();
    }
}

    /* -------------------------------------------------------------------------- */
    /*ANCHOR                                  カスタムメソッド                                  */
    /* -------------------------------------------------------------------------- */

    /**
     * バリデーションエラーflashメソッド
     *
     * @param [type] $errors
     * @param [type] $action
     * @return void
     */
    protected function _flashError($errors = null)
    {
        // バリデーションエラー発生時、エラー表示＆リダイレクト
        foreach ($errors as $error => $value) {
            foreach ($value as $single_error) {
                $this->Flash->error(__($single_error));
            }
        }
        return $this->redirect(['controller' => 'TBooks', 'action' => 'index']);
    }

    /**
     * csv出力メソッド
     */
    protected function _csvIndexExport($tHistories){
        $histories = $tHistories->toArray();
        $csvBooks = [];
        foreach($histories as $history){
            $genres = '';
            foreach($history->t_book->m_genres as $genre){
                $genres .= $genre->genre.',';
            }
            $history->t_book['genres'] = $genres;
            $csvBooks[] = [
                $history->id,
                $history->t_book['image'],
                $history->t_book['name'],
                $history->t_book['book_no'],
                $history->t_book['genres'],
                $history->rental_time->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                $history->t_book['remain'],
                $history->t_book['quantity'],
                $history->t_book['price'],
                $history->t_book['outline'],
            ];
        };
        $data = $csvBooks;
        $_serialize = ['data'];
        $_header = [
            'ID','画像名', '名前', '書籍No','ジャンル','最終レンタル日','在庫','登録冊数','価格','概要',
        ];
        $_csvEncoding = 'CP932';
        $_newline = "\r\n";
        $_eol = "\r\n";
        $this->setResponse($this->getResponse()->withDownload('HistoryBooks.csv'));
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('data', '_serialize', '_header', '_csvEncoding', '_newline', '_eol'));
    }
}