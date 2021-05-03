<?php

declare(strict_types=1);

namespace App\Controller;


use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Exception\NotFoundException;

/**
 * TBooks Controller
 *
 * @property \App\Model\Table\TBooksTable $TBooks
 * @method \App\Model\Entity\TBook[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TBooksController extends AppController
{

    /* -------------------------------------------------------------------------- */
    /*ANCHOR                                    書籍一覧                                    */
    /* -------------------------------------------------------------------------- */

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // 検索データ取得
        $inputName = $this->request->getQuery('search_books');
        $inputGenre = $this->request->getQuery('genre');
        $csv = $this->request->getQuery('csv');

        $this->paginate = [
            'sortableFields' => [
                'id', 'name', 'book_no', 'MGenres.id', 'deadline', 'remain', 'quantity'
            ],
            'contain' =>  ['MGenres']
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
            $tBooks = $this->paginate($this->TBooks->find('all')->where(['TBooks.del_flg' => 0,'TBooks.name LIKE' => "%{$sqlName}%" ])->contain(['MGenres'])->matching('MGenres', function ($q) use ($id) {return $q->where(['MGenres.id' => $id]);
            }));
        }else{
            $tBooks = $this->paginate($this->TBooks->find('all')->where(['TBooks.del_flg' => 0,'TBooks.name LIKE' => "%{$sqlName}%" ]));
        }

         /* -------------------------------- csv出力メソッド ------------------------------- */
        if($csv == 1){
            $this->_csvExport($tBooks);
        }

        $genres = $this->TBooks->MGenres->find()->where(['del_flg'=> 0]);
        $this->set(compact('genres'));
        $this->set(compact('tBooks'));

    }

    /**
     * View method
     *
     * @param string|null $id T Book id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {

        $tBook = $this->TBooks->get($id, [
            'contain' => ['MGenres', 'TFavorites'],
            ]);

            $genres = [];
            $mGenres = $tBook->m_genres;
            foreach ($mGenres as $genre) {
                $genres[] = $genre->genre;
            }
            if($this->Session->read('User.id')){
                $user_id = $this->Session->read('User.id');
                $tFavorites = $this->TBooks->TFavorites->find('list')->where(['TFavorites.t_books_id' => $tBook['id'], 'TFavorites.del_flg' => 0, 'TFavorites.m_users_id' => $user_id]);
                $this->set(compact('tFavorites'));
            }
        $this->set(compact('genres'));
        $this->set(compact('tBook'));
    }



    /* -------------------------------------------------------------------------- */
    /*// ANCHOR                                 登録処理                                    */
    /* -------------------------------------------------------------------------- */

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */

    public function add()
    {
        $this->userValid();


        $tBook = $this->TBooks->newEmptyEntity();

        if ($this->request->is('post')) {

            // データ取得
            $data = $this->request->getData();

            /* --------------------------------- ジャンル処理 --------------------------------- */

            // ジャンル未入力時に早期リダイレクト
            if (!($this->request->getData('genres'))) {
                $this->Flash->error(__('ジャンルを入力してください'));
                return $this->redirect(['action' => 'add']);
            }

            // 入力されたジャンルのID取得
            $genres = array_filter(explode(',', ($this->request->getData('genres'))));
            foreach ($genres as $genre) {
                $genreId[] = explode(':', trim($genre))[0];
            }
            // ジャンルIDをデータに
            $data['genres'] =  $genreId;

            /* ---------------------------------- 画像処理 ---------------------------------- */

            // 画像ファイル取得
            $file =  $this->request->getData('image_file');
            // 画像名登録関数
            $data = $this->_imageName($data);

            /* ---------------------------------- データ処理 --------------------------------- */

            // 書籍エンティティセット、バリデーション実行
            $tBook = $this->TBooks->patchEntity(
                $tBook,
                $data
            );

            // バリデーションエラー時、フラッシュ＆リダイレクト関数
            if (!empty($tBook->getErrors())) {
                $this->_flashError($tBook->getErrors(), 'add');
            }

            /* ------------------------------- バリデーション後処理 ------------------------------- */

            // ファイルアップロードメソッド
            $newPath =  $this->_imageUpload($file, $data['image']);

            // 初期の在庫数は冊数に固定
            $tBook->remain =  $this->request->getData('quantity');

            // セッション書き込みメソッド
            // 概要＝>成型後,ジャンル=>成型前,
            $this->_sessionWrite($tBook, $file, $newPath, $genres, '');

            return $this->redirect(['action' => 'addConfirm']);
        }

        // ジャンル情報取得
        $genres = $this->TBooks->MGenres->find();

        //TODO 書籍No重複の事前確認
        // $allBooks = ($this->TBooks->find()->all())->toArray();

        // $this->set(compact('allBooks'));
        $this->set(compact('genres'));
        $this->set(compact('tBook'));
    }

    /* -------------------------------------------------------------------------- */
    /*// ANCHOR                                 初期確認メソッド                                  */
    /* -------------------------------------------------------------------------- */

    /**
     * 書籍初期登録確認メソッド
     *
     * @return void
     */
    public function addConfirm()
    {
        $this->userValid();
        if (!($this->Session->check('book'))) {
            return $this->redirect(['action' => 'add']);
        }

        $tBook = $this->Session->read('book');
        $this->set(compact('tBook'));
    }

    /* -------------------------------------------------------------------------- */
    /*// ANCHOR                                  初期登録メソッド                                  */
    /* -------------------------------------------------------------------------- */

    /**
     *書籍初期登録メソッド
     *
     * @return void
     */
    public function addExec()
    {
        $this->userValid();

        // TODO アップロード画像の2度目のバリデーション
        if ($this->request->is('post')) {

            // トランザクション
            $connection = ConnectionManager::get('default');
            $connection->begin();
            try {


                $data = $this->Session->read('book');
                // debug($data);
                // デバッグテスト
                // if (!empty($data)) {
                    //     throw new NotFoundException(__('データが見つかりません'));
                    // }

                /* ---------------------------------- 書籍データ処理 --------------------------------- */

                // 画像対象外バリデーション実行
                $tBook = $this->TBooks->newEntity(
                    $data,
                    ['validate' => 'noImage']
                );


                // バリデーションエラー時、フラッシュ＆リダイレクト関数
                if (!empty($tBook->getErrors())) {
                    $this->_flashError($tBook->getErrors(), 'add');
                }

                /* -------------------------------- ジャンルデータ処理 ------------------------------- */

                if ($this->TBooks->save($tBook)) {

                    // 書籍ジャンル 中間テーブルジャンル保存メソッド
                    $this->_genreSave($tBook);

                    //   コミット
                    $connection->commit();
                    $this->Flash->success(__('書籍情報の登録に成功しました'));
                    $this->Session->delete('book');
                    return $this->redirect(['action' => 'view', $tBook['id']]);
                }
            } catch (\Exception $e) {

                // ロールバック
                $connection->rollback();
                $this->Flash->error(__('書籍情報の登録に失敗しました'));
                unlink($data['image_path']);
                return $this->redirect(['action' => 'add']);
            }
        }
        $this->Flash->error(__('アクセスエラーが発生しました'));
        return $this->redirect(['action' => 'add']);
    }


    /* -------------------------------------------------------------------------- */
    /* // ANCHOR                                   編集処理                                    */
    /* -------------------------------------------------------------------------- */
    /**
     * Edit method
     *
     * @param string|null $id T Book id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    public function edit($id = null)
    {
        $this->userValid();

        $tBook = $this->TBooks->get($id, [
            'contain' => ['MGenres']
        ]);


        /* ------------------------------- ジャンル情報取得 ------------------------------ */

        $genres = $this->TBooks->MGenres->find()->where(['del_flg'=> 0]);
        $selectGenres = [];
        $beforeGenres = '';
        $mGenres = $tBook->m_genres;
        foreach ($mGenres as $genre) {
            $selectGenres[] = $genre->genre;
            $beforeGenres .=  $genre->id . ':' . $genre->genre . ',';
        }


        if ($this->request->is(['patch', 'post', 'put'])) {

            // データ取得
            $data = $this->request->getData();

            /* -------------------------------- version取得 ------------------------------- */
            $version = $data['version'];

            /* ------------------------------- ジャンル処理メソッド ------------------------------- */

            // ジャンル未入力時は既存ジャンルを入力
            if ($this->request->getData('genres')) {
                $inputGenres = $this->request->getData('genres');
            } else {
                $inputGenres = '';
                foreach (($tBook->m_genres) as $genre) {
                    $Genres[] = $genre->id . ':' . $genre->genre . ',';
                }
                foreach ($Genres as $g) {
                    $inputGenres .= $g;
                }
            }

            // 入力されたジャンルのID取得
            $genres = array_filter(explode(',', ($inputGenres)));
            foreach ($genres as $genre) {
                $genreId[] = explode(':', trim($genre))[0];
            }

            // ジャンルIDをデータに
            $data['genres'] =  $genreId;

            /* -------------------------------- 画像処理メソッド -------------------------------- */

            // 画像ファイル取得
            $file =  $this->request->getData('image_file');

            // 画像名登録関数
            $data = $this->_imageName($data);


            /* -------------------------------- データ処理メソッド ------------------------------- */

            // 書籍エンティティセット、バリデーション実行
            $tBook = $this->TBooks->patchEntity(
                $tBook,
                $data,
                ['validate' => 'noImage']
            );

            // バリデーションエラー時、フラッシュ＆リダイレクト関数
            if (!empty($tBook->getErrors())) {
                $this->_flashError($tBook->getErrors(), 'edit', $id);
            }

            /* ------------------------------ バリデーション後メソッド ------------------------------ */


            // ファイルアップロード関数
            // 送られていなかった場合は既存の画像をあてはめ
            if ($file->getClientFilename() == '') {
                $newPath =  $this->request->getData('image');
                $tBook->image = $this->request->getData('image');
                $file = '';
            } else {
                // 画像送られていた場合はアップロード
                $newPath =  $this->_imageUpload($file, $data['image']);
            }

            // セッション書き込み ジャンル=>成型前,
            $this->_sessionWrite($tBook, $file, $newPath, $genres, $version);

            return $this->redirect(['action' => 'editConfirm']);
        }

        $this->set(compact('selectGenres'));
        $this->set(compact('beforeGenres'));
        $this->set(compact('genres'));
        $this->set(compact('tBook'));
    }

    /* -------------------------------------------------------------------------- */
    /*  // ANCHOR                                  編集確認                                    */
    /* -------------------------------------------------------------------------- */
    /**
     * 編集確認メソッド
     *
     * @return void
     */


    public function editConfirm()
    {
        $this->userValid();

        // セッション有無で早期リダイレクト
        if (!($this->Session->check('book'))) {
            return $this->redirect(['action' => 'edit']);
        }
        $tBook = $this->Session->read('book');

        $this->set(compact('tBook'));
    }

    /* -------------------------------------------------------------------------- */
    /*// ANCHOR                                   更新メソッド                                   */
    /* -------------------------------------------------------------------------- */

    /**
     * 更新メソッド
     *
     * @return void
     */
    public function editExec()
    {

        $this->userValid();

        // TODO アップロード画像の2度目のバリデーション
        if ($this->request->is('post')) {

            // トランザクション開始
            $connection = ConnectionManager::get('default');
            $connection->begin();
            try {
                $data = $this->Session->read('book');

                $tBook = $this->TBooks->get($data['id'], [
                    'contain' => ['MGenres'],
                ]);

                /* ---------------------------------- Entity --------------------------------- */
                $tBook = $this->TBooks->patchEntity(
                    $tBook,
                    $data,
                    ['validate' => 'noImage']
                );

                // バリデーションエラー時、フラッシュ＆リダイレクトメソッド
                if (!empty($tBook->getErrors())) {
                    $this->_flashError($tBook->getErrors(), 'edit', $data['id']);
                }


                /* ---------------------------- versionチェック　排他制御 ---------------------------- */
                $versionNow = ($tBook['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss'));
                if ($versionNow !== $data['version']) {
                    $this->Flash->error(__('更新エラー：更新前に書籍情報の変更が行われました。'));
                    return $this->redirect(['action' => 'view', $data['id']]);
                }


                /* ---------------------------------- 成功時処理 --------------------------------- */

                if ($this->TBooks->save($tBook)) {

                    // 書籍ジャンル中間テーブル保存メソッド
                    $this->_genreSave($tBook);

                    // コミット
                    $connection->commit();

                    $this->Flash->success(__('書籍情報の更新に成功しました'));
                    $this->Session->delete('book');
                    return $this->redirect(['action' => 'view', $tBook['id']]);
                }
            } catch (\Exception $e) {

                /* ---------------------------------- 失敗時処理 --------------------------------- */

                // ロールバック
                $connection->rollback();
                $this->Flash->error(__('書籍情報の更新に失敗しました'));
                return $this->redirect(['action' => 'edit']);
            }
        }
        $this->Flash->error(__('アクセスエラーが発生しました'));
        return $this->redirect(['action' => 'edit']);
    }

    /* -------------------------------------------------------------------------- */
    /*// ANCHOR                                   削除メソッド                                   */
    /* -------------------------------------------------------------------------- */

    /**
     * 削除メソッド
     *
     * @param string|null $id T Book id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $this->userValid();

        // 書籍は削除フラグを立て、論理削除
        $tBook = $this->TBooks->get($id);
        if ($this->request->allowMethod(['post', 'delete'])) {
            $tBook = $this->TBooks->patchEntity(
                $tBook,
                ['del_flg' => 1]
            );
            if ($this->TBooks->save($tBook)) {
                $this->Flash->success(__('書籍の削除に成功しました。'));
            } else {
                $this->Flash->error(__('書籍の削除に失敗しました。'));
            }
        }

        return $this->redirect(['action' => 'index']);
    }



    /* -------------------------------------------------------------------------- */
    /*// ANCHOR                                  カスタムメソッド                                  */
    /* -------------------------------------------------------------------------- */


    /*
     * 画像名セットメソッド
     *
     * @param [type] $data
     * @return void
     */
    protected function _imageName($data = null)
    {
        // 拡張子切り出し
        $extension =  substr($data['image_file']->getClientFilename(), -3, 3);
        // 画像名作成
        $imageName = date('YmdHis') . '_' . $data['book_no'] . '.' . $extension;
        // 画像名を事前代入
        $data['image'] = $imageName;

        return $data;
    }

    /**
     * 画像アップロードメソッド
     *
     * @param [type] $file
     * @param [type] $imageName
     * @return void
     */
    protected function _imageUpload($file = null, $imageName = null)
    {
        // 元の名前でファイルアップロード
        $name = $file->getClientFilename();
        $oldPath = WWW_ROOT . 'img' . DS . $name;
        $file->moveTo($oldPath);

        // データベース保存した名前にリネーム
        $newPath = WWW_ROOT . 'img' . DS . $imageName;
        rename($oldPath, $newPath);
        return $newPath;
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
     * セッション書き込みメソッド
     *
     * @param [type] $tBook
     * @param [type] $file
     * @param [type] $newPath
     * @param [type] $genres
     * @return void
     */
    protected function _sessionWrite($tBook, $file, $newPath, $genres, $version)
    {
        $this->Session->write(
            ['book' => [
                'id' => $tBook->id,
                'name' => $tBook->name,
                'book_no' => $tBook->book_no,
                'quantity' => $tBook->quantity,
                'price' => $tBook->price,
                'deadline' => $tBook->deadline,
                'outline' => $tBook->outline,
                'image' => $tBook->image,
                'image_file' => $file,
                'remain' => $tBook->remain,
                'del_flg' => $tBook->del_flg,
                'image_path' =>  $newPath,
                'genres' => $tBook->genres,
                'genres_name' => $genres,
                'version' => $version,
            ]]
        );
    }

    /**
     *  中間テーブルへの書籍登録ジャンルの保存
     */
    protected function _genreSave($tBook)
    {

        $genres = $tBook->genres;
        $tBookGenres = TableRegistry::getTableLocator()->get('TBookGenres');
        $tBookGenres->deleteAll(['t_books_id' => $tBook->id]);

        foreach ($genres as $key => $value) {
            $data = [
                't_books_id' =>  $tBook->id,
                'm_genres_id' => $value,
                'del_flg' => false,
            ];
            $tBookGenre = $tBookGenres->newEntity($data);
            $tBookGenres->save($tBookGenre);
        }
    }
    /**
     * ユーザー権限によるリダイレクト処理
     */
    protected function userValid()
    {
        if ($this->Session->read('User.role') != 3 && $this->Session->read('User.role') != 2) {
            $this->Flash->error(__('担当者以上の権限がありません'));
            return $this->redirect(['controller' => 'TBooks', 'action' => 'index']);
        }
    }

    /**
     * csv出力メソッド
     */
    protected function _csvExport($tBooks){
        $books = $tBooks->toArray();
        $csvBooks = [];
        foreach($books as $book){
            $genres = '';
            foreach($book->m_genres as $genre){
                $genres .= $genre->genre.',';
            }
            $book['genres'] = $genres;
            $csvBooks[] = [
                $book['id'],
                $book['image'],
                $book['name'],
                $book['book_no'],
                $book['genres'],
                $book['deadline'],
                $book['remain'],
                $book['quantity'],
                $book['price'],
                $book['outline'],
            ];
        };
        $data = $csvBooks;
        $_serialize = ['data'];
        $_header = [
            'ID','画像名', '名前', '書籍No','ジャンル','最大レンタル時間','在庫','登録冊数','価格','概要',
        ];
        $_csvEncoding = 'CP932';
        $_newline = "\r\n";
        $_eol = "\r\n";
        $this->setResponse($this->getResponse()->withDownload('Books.csv'));
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('data', '_serialize', '_header', '_csvEncoding', '_newline', '_eol'));
    }
}