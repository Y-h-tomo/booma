<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TBookGenre Entity
 *
 * @property int $id
 * @property int $t_books_id
 * @property int $m_genres_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property bool|null $del_flg
 *
 * @property \App\Model\Entity\TBook $t_book
 * @property \App\Model\Entity\MGenre $m_genre
 */
class TBookGenre extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        't_books_id' => true,
        'm_genres_id' => true,
        'created' => true,
        'modified' => true,
        'del_flg' => true,
        't_book' => true,
        'm_genre' => true,
    ];
}
