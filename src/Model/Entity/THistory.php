<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * THistory Entity
 *
 * @property int $id
 * @property int $t_books_id
 * @property int $m_users_id
 * @property \Cake\I18n\FrozenTime $return_time
 * @property bool $del_flg
 * @property int $arrears
 * @property \Cake\I18n\FrozenTime $rental_time
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 *
 */
class THistory extends Entity
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
        'm_users_id' => true,
        'return_time' => true,
        'del_flg' => true,
        'arrears' => true,
        'rental_time' => true,
        'created' => true,
        'modified' => true,
        't_book' => true,
        'm_user' => true,
    ];
}
