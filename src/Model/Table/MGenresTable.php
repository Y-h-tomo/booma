<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MGenres Model
 *
 * @method \App\Model\Entity\MGenre newEmptyEntity()
 * @method \App\Model\Entity\MGenre newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\MGenre[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MGenre get($primaryKey, $options = [])
 * @method \App\Model\Entity\MGenre findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\MGenre patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MGenre[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MGenre|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MGenre saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MGenre[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MGenre[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\MGenre[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MGenre[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MGenresTable extends Table
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

        $this->setTable('m_genres');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('TBooks', [
            'foreignKey' => 'm_genres_id',
            'targetForeignKey' => 't_books_id',
            'joinTable' => 't_book_genres',
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
            ->scalar('genre')
            ->maxLength('genre', 50)
            ->requirePresence('genre', 'create')
            ->notEmptyString('genre');

        $validator
            ->boolean('del_flg')
            ->allowEmptyString('del_flg');

        return $validator;
    }
}