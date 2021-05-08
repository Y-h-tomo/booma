<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MUsers Model
 *
 * @method \App\Model\Entity\MUser newEmptyEntity()
 * @method \App\Model\Entity\MUser newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\MUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\MUser findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\MUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MUser[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MUser|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MUser saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MUser[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MUser[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\MUser[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MUser[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MUsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('m_users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->hasMany('THistories', [
            'foreignKey' => 'm_users_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('TFavorites', [
            'foreignKey' => 'm_users_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('TScores', [
            'foreignKey' => 'm_users_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->Integer('user_no')
            ->range('user_no', [1, 999999999], 'ユーザーNoは最大９桁です')
            ->requirePresence('user_no', 'create')
            ->notEmptyString('user_no', 'ユーザーNoは入力必須です。')
            ->add('user_no', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'ユーザーNoが重複しています。']);

        $validator
            ->scalar('name')
            ->maxLength('name', 50, 'ユーザー名は最大50桁です。')
            ->requirePresence('name', 'create')
            ->notEmptyString('name', 'ユーザー名は入力必須です。');

        $validator
            ->Integer('login_no')
            ->range('login_no', [1, 999999999], 'ログインNoは最大９桁です')
            ->requirePresence('login_no', 'create')
            ->notEmptyString('login_no', 'ログインNoは入力必須です。')
            ->add('login_no', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'ログイン Noが重複しています。']);

        $validator
            ->scalar('password')
            ->requirePresence('password', 'create')
            ->notEmptyString('password', 'パスワードは入力必須です。');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email', 'Emailは入力必須です。')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'Email が重複しています。']);

        $validator
            ->integer('role')
            ->range('role', [1, 3], '権限範囲は１～３までです。')
            ->requirePresence('role', 'create')
            ->notEmptyString('role', '権限は入力必須です。');

        $validator
            ->boolean('del_flg')
            ->allowEmptyString('del_flg');

        return $validator;
    }
}
