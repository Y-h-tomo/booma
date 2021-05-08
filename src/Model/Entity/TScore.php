<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TScore Entity
 *
 * @property int $id
 * @property int $m_users_id
 * @property int $t_books_id
 * @property string $score
 * @property bool $del_flg
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\MUser $m_user
 * @property \App\Model\Entity\TBook $t_book
 */
class TScore extends Entity
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
        'm_users_id' => true,
        't_books_id' => true,
        'score' => true,
        'del_flg' => true,
        'created' => true,
        'modified' => true,
        'm_user' => true,
        't_book' => true,
    ];
}
