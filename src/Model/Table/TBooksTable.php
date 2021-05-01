<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TBooks Model
 *
 * @method \App\Model\Entity\TBook newEmptyEntity()
 * @method \App\Model\Entity\TBook newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\TBook[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TBook get($primaryKey, $options = [])
 * @method \App\Model\Entity\TBook findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\TBook patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TBook[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\TBook|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TBook saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TBook[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TBook[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\TBook[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TBook[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TBooksTable extends Table
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

        $this->setTable('t_books');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        // $this->belongsToMany('mGenres', [
        //     'joinTable' => 't_book_genres',
        // ]);
        $this->belongsToMany('MGenres', [
            'foreignKey' => 't_books_id',
            'targetForeignKey' => 'm_genres_id',
            'joinTable' => 't_book_genres'
        ]);

        $this->hasMany('TBookGenres', [
            'foreignKey' => 't_books_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('THistories', [
            'foreignKey' => 'id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('TFavorites', [
            'foreignKey' => 'id',
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
            ->scalar('name')
            ->maxLength('name', 50, '書籍名は最大50文字です')
            ->requirePresence('name', 'create', '書籍名は入力必須です')
            ->notEmptyString('name', '書籍名は入力必須です');

        $validator
            ->integer('book_no', '書籍Noは数字で入力してください')
            ->maxLength('book_no', 30, '書籍Noは最大30文字です')
            ->requirePresence('book_no', 'create', '書籍Noは入力必須です')
            ->notEmptyString('book_no', '書籍Noは入力必須です')
            ->add('book_no', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => '書籍Noが重複しています。']);

        $validator
            ->integer('quantity')
            ->maxLength('quantity', 3)
            ->range('quantity', [0, 999], '冊数は0~999までです')
            ->requirePresence('quantity', 'create', '冊数は入力必須です')
            ->notEmptyString('quantity', '冊数は入力必須です');

        $validator
            ->scalar('price')
            ->maxLength('price', 10, '価格10桁までです')
            ->notEmptyString('price', '価格は入力必須です');

        $validator
            ->integer('deadline', 'レンタル時間は数字で入力してください')
            ->range('deadline', [0, 999], 'レンタル時間は0~999までです')
            ->requirePresence('deadline', 'create', 'レンタル時間は入力必須です')
            ->notEmptyString('deadline', 'レンタル時間は入力必須です');

        $validator
            ->scalar('outline')
            ->maxLength('outline', 200, '概要は200文字までです')
            ->allowEmptyString('outline');

        $validator
            ->maxLength('remain', 3)
            ->range('remain', [0, 999])
            ->allowEmptyString('remain');

        $validator
            ->boolean('del_flg')
            ->allowEmptyString('del_flg');

        $validator
            ->scalar('image')
            ->maxLength('image', 50)
            ->requirePresence('image', 'create')
            ->allowEmptyString('image', null, 'create');

        $validator
            ->requirePresence('image_file', 'create', '画像は入力必須です')
            ->notEmptyFile('image_file', '画像は入力必須です')
            ->uploadedFile('image_file', ['maxSize' => '100KB'], '画像サイズは100KBまでです。')
            ->add(
                'image_file',
                'extension',
                [
                    'rule' => 'extension',
                    'message' => '使用できる拡張子はgif,jpeg,png,jpgです'
                ]
            );
        return $validator;
    }

    public function validationNoImage($validator)
    {

        $validator
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 50, '書籍名は最大50文字です')
            ->requirePresence('name', 'create', '書籍名は入力必須です')
            ->notEmptyString('name');

        $validator
            ->integer('book_no', '書籍Noは数字で入力してください')
            ->maxLength('book_no', 30, '書籍Noは最大30文字です')
            ->requirePresence('book_no', 'create', '書籍Noは入力必須です')
            ->notEmptyString('book_no')
            ->add('book_no', 'unique', ['rule' => 'validateUnique', 'provider' => 'table'], '書籍Noが重複しています。');

        $validator
            ->integer('quantity')
            ->maxLength('quantity', 3)
            ->range('quantity', [0, 999])
            ->requirePresence('quantity', 'create', '冊数は入力必須です')
            ->notEmptyString('quantity');

        $validator
            ->scalar('price')
            ->maxLength('price', 10, '価格は10桁までです')
            ->allowEmptyString('price');

        $validator
            ->integer('deadline')
            ->range('deadline', [0, 999])
            ->requirePresence('deadline', 'create', 'レンタル時間は入力必須です')
            ->notEmptyString('deadline');

        $validator
            ->scalar('outline')
            ->maxLength('outline', 200, '概要は200文字までです')
            ->allowEmptyString('outline');

        $validator
            ->maxLength('remain', 3)
            ->range('remain', [0, 999])
            ->allowEmptyString('remain');

        $validator
            ->boolean('del_flg')
            ->allowEmptyString('del_flg');

        $validator
            ->scalar('image')
            ->maxLength('image', 50)
            ->requirePresence('image', 'create')
            ->allowEmptyString('image', null, 'create');

        $validator
            ->scalar('image')
            ->maxLength('image', 50)
            ->requirePresence('image', 'create')
            ->allowEmptyString('image', null, 'create');
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
        $rules->add($rules->isUnique(['book_no']), ['errorField' => 'book_no']);

        return $rules;
    }
}