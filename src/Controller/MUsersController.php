<?php

declare(strict_types=1);

namespace App\Controller;


use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Exception\NotFoundException;

/**
 * MUsers Controller
 *
 * @property \App\Model\Table\MUsersTable $MUsers
 * @method \App\Model\Entity\MUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MUsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->userValid();
        // 検索クエリ取得
        $inputName = $this->request->getQuery('search_users');
        $inputRole = $this->request->getQuery('search_role');
        $csv = $this->request->getQuery('csv');

        $this->paginate = [
            'sortableFields' => [
                'user_no', 'name', 'login_no', 'email', 'role'
            ],
            'contain' =>  ['THistories']
        ];

          // ユーザー名曖昧検索
          if(!empty($inputName)){
            $sqlName = $inputName;
        } else {
            $sqlName = '';
        }

        // 権限でフィルタリング
        if (!empty($inputRole)) {
            $query = $this->MUsers->find()->where(['del_flg' => 0,'name LIKE' => "%{$sqlName}%",'role' => $inputRole]);
        } else {
            $query = $this->MUsers->find()->where(['del_flg' => 0,'name LIKE' => "%{$sqlName}%"]);
        }

        $mUsers = $this->paginate($query);

         /* -------------------------------- csv出力メソッド ------------------------------- */
        if($csv == 1){
            $this->_csvExport($mUsers);
        }

        $this->set(compact('mUsers'));
    }

    /**
     * View method
     *
     * @param string|null $id M User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->userValid();

        $mUser = $this->MUsers->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('mUser'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->userValid();

        $mUser = $this->MUsers->newEmptyEntity();
        if ($this->request->is('post')) {
            $mUser = $this->MUsers->patchEntity(
                $mUser,
                $this->request->getData()
            );

            if (!empty($mUser->getErrors())) {
                $this->_flashError($mUser->getErrors(), 'add');
            }

            if ($this->MUsers->save($mUser)) {
                $this->Flash->success(__('ユーザー登録に成功しました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('ユーザー登録に失敗しました。'));
        }
        $this->set(compact('mUser'));
    }

    /**
     * Edit method
     *
     * @param string|null $id M User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->userValid();

        $mUser = $this->MUsers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $mUser = $this->MUsers->patchEntity($mUser, $this->request->getData());

            if (!empty($mUser->getErrors())) {
                $this->_flashError($mUser->getErrors(), 'edit', $id);
            }

            if ($this->MUsers->save($mUser)) {
                $this->Flash->success(__('ユーザー編集を完了しました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('ユーザー編集に失敗しました。'));
        }
        $this->set(compact('mUser'));
    }

    /**
     * Delete method
     *
     * @param string|null $id M User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $this->userValid();

        $mUser = $this->MUsers->get($id);
        if ($this->request->allowMethod(['post', 'delete'])) {
            $mUser = $this->MUsers->patchEntity(
                $mUser,
                ['del_flg' => 1]
            );
            if ($this->MUsers->save($mUser)) {
                $this->Flash->success(__('ユーザーを削除しました。'));
            } else {
                $this->Flash->error(__('ユーザーの削除に失敗しました。'));
            }
        }

        return $this->redirect(['action' => 'index']);
    }


    /**
     * ANCHOR ログイン認証
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // ログインアクションを認証を必要としないように設定することで、
        // 無限リダイレクトループの問題を防ぐことができます
        // ! ユーザーゼロから追加の場合,add も許可する
        $this->Authentication->addUnauthenticatedActions(['login','add']);
    }
    /**
     * ログインメソッド
     *
     * @return void
     */
    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        $user = $result->getData();
        // POSTやGETに関係なく、ユーザーがログインしていればリダイレクトします
        $session = $this->getRequest()->getSession();

        if ($result->isValid()) {

            // ユーザー情報を格納
            $session->write([
                'User.id' => $user->id,
                'User.name' => $user->name,
                'User.role' => $user->role
            ]);

            // ログイン成功後にリダイレクトします
            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'TBooks',
                'action' => 'index',
            ]);

            $this->Flash->success(__('ログインに成功しました。'));
            return $this->redirect($redirect);
        }
        // ユーザーの送信と認証に失敗した場合にエラーを表示します
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('ログインNo、またはパスワードが間違っています。'));
        }
    }
    /**
     * ログアウトメソッド
     *
     * @return void
     */
    public function logout()
    {
        $result = $this->Authentication->getResult();
        // セッションをクリア
        $session = $this->getRequest()->getSession();
        $session->destroy();
        // POSTやGETに関係なく、ユーザーがログインしていればリダイレクトします
        if ($result->isValid()) {
            $this->Authentication->logout();
            return $this->redirect(['controller' => 'MUsers', 'action' => 'login']);
        }
        return $this->redirect(['controller' => 'TBooks', 'action' => 'index']);
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
    protected function _csvExport($mUsers)
    {
        $users = $mUsers->toArray();
        $csvUsers = [];
        foreach($users as $user){
            $roleName ='';
            switch($user['role']){
                    case 1:
                        $roleName = '1：一般ユーザー' ;
                        break;
                    case 2:
                        $roleName = '2：担当者' ;
                        break;
                    case 3:
                        $roleName = '3：管理者' ;
                        break;
                    default:
                        $roleName = '権限なし' ;
                        break;
                        }
            $csvUsers[] = [
                $user['id'],
                $user['user_no'],
                $user['name'],
                $user['login_no'],
                $user['email'],
                $roleName,
            ];
        };
        $data = $csvUsers;
        $_serialize = ['data'];
        $_header = [
            'ID','ユーザーNo', '名前', 'ログインNo','Email','権限',
        ];
        $_csvEncoding = 'CP932';
        $_newline = "\r\n";
        $_eol = "\r\n";
        $this->setResponse($this->getResponse()->withDownload('Users.csv'));
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('data', '_serialize', '_header', '_csvEncoding', '_newline', '_eol'));
    }
}