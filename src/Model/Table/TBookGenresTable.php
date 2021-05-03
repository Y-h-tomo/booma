<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TBookGenres Model
 *
 * @property \App\Model\Table\TBooksTable&\Cake\ORM\Association\BelongsTo $TBooks
 * @property \App\Model\Table\MGenresTable&\Cake\ORM\Association\BelongsTo $MGenres
 *
 * @method \App\Model\Entity\TBookGenre newEmptyEntity()
 * @method \App\Model\Entity\TBookGenre newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\TBookGenre[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TBookGenre get($primaryKey, $options = [])
 * @method \App\Model\Entity\TBookGenre findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\TBookGenre patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TBookGenre[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\TBookGenre|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TBookGenre saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TBookGenre[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TBookGenre[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\TBookGenre[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TBookGenre[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TBookGenresTable extends Table
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

        $this->setTable('t_book_genres');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('TBooks', [
            'foreignKey' => 't_books_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('MGenres', [
            'foreignKey' => 'm_genres_id',
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
            ->boolean('del_flg')
            ->allowEmptyString('del_flg');

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
        $rules->add($rules->existsIn(['m_genres_id'], 'MGenres'), ['errorField' => 'm_genres_id']);

        return $rules;
    }
}