<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TBook Entity
 *
 * @property int $id
 * @property string $name
 * @property int $book_no
 * @property int $quantity
 * @property string $price
 * @property int $deadline
 * @property string|null $outline
 * @property int|null $remain
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property bool|null $del_flg
 * @property string $image
 * @property int $genres
 */
class TBook extends Entity
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
        'name' => true,
        'book_no' => true,
        'quantity' => true,
        'price' => true,
        'deadline' => true,
        'outline' => true,
        'remain' => true,
        'created' => true,
        'modified' => true,
        'del_flg' => true,
        'image' => true,
        'genres' => true,
    ];
}
