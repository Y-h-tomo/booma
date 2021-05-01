<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TFavorites Model
 *
 * @property \App\Model\Table\TBooksTable&\Cake\ORM\Association\BelongsTo $TBooks
 * @property \App\Model\Table\MUsersTable&\Cake\ORM\Association\BelongsTo $MUsers
 *
 * @method \App\Model\Entity\TFavorite newEmptyEntity()
 * @method \App\Model\Entity\TFavorite newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\TFavorite[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TFavorite get($primaryKey, $options = [])
 * @method \App\Model\Entity\TFavorite findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\TFavorite patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TFavorite[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\TFavorite|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TFavorite saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TFavorite[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TFavorite[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\TFavorite[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TFavorite[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TFavoritesTable extends Table
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

        $this->setTable('t_favorites');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('TBooks', [
            'foreignKey' => 't_books_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('MUsers', [
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->boolean('del_flg')
            ->requirePresence('del_flg', 'create')
            ->notEmptyString('del_flg');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['t_books_id'], 'TBooks'), ['errorField' => 't_books_id']);
        $rules->add($rules->existsIn(['m_users_id'], 'MUsers'), ['errorField' => 'm_users_id']);

        return $rules;
    }
}
