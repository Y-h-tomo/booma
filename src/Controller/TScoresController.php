<?php
declare(strict_types=1);

namespace App\Controller;


use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Exception\NotFoundException;

/**
 * TScores Controller
 *
 * @property \App\Model\Table\TScoresTable $TScores
 * @method \App\Model\Entity\TScore[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TScoresController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['MUsers', 'TBooks'],
        ];
        $tScores = $this->paginate($this->TScores);

        $this->set(compact('tScores'));
    }

    /**
     * View method
     *
     * @param string|null $id T Score id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $tScore = $this->TScores->get($id, [
            'contain' => ['MUsers', 'TBooks'],
        ]);

        $this->set(compact('tScore'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $tScore = $this->TScores->newEmptyEntity();
        if ($this->request->is('post')) {

             // トランザクション
            $connection = ConnectionManager::get('default');
            $connection->begin();
            try {
                // del_flg=0で新規登録
                $data = $this->request->getData();
                $data['del_flg'] = 0;
                $tScore = $this->TScores->patchEntity($tScore, $data);
                if ($this->TScores->save($tScore)) {
                    //   コミット
                    $connection->commit();

                    $avgQuery =  $this->TScores->find()->where(['t_books_id' => $data['t_books_id'], 'del_flg' => 0]);
                    $avgScore = $avgQuery->select(['avg' => $avgQuery->func()->avg('score')])->toArray()[0];
                    echo $avgScore->avg;
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
     * Edit method
     *
     * @param string|null $id T Score id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            // トランザクション
            $connection = ConnectionManager::get('default');
            $connection->begin();
            try {
            $data = $this->request->getData();
            $tScore = $this->TScores->get($data['id']);
            $tScore = $this->TScores->patchEntity($tScore, $data);
            if ($this->TScores->save($tScore)) {
                //   コミット
                $connection->commit();

                $avgQuery =  $this->TScores->find()->where(['t_books_id' => $data['t_books_id'], 'del_flg' => 0]);
                $avgScore = $avgQuery->select(['avg' => $avgQuery->func()->avg('score')])->toArray()[0];
                echo $avgScore->avg;
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
     * @param string|null $id T Score id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $tScore = $this->TScores->get($id);
        if ($this->TScores->delete($tScore)) {
            $this->Flash->success(__('The t score has been deleted.'));
        } else {
            $this->Flash->error(__('The t score could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}