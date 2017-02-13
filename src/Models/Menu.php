<?php

namespace Phambinh\Appearance\Models;

use Illuminate\Database\Eloquent\Model;
use Phambinh\Laravel\Database\Traits\Model as PhambinhModel;
use Phambinh\Laravel\Database\Traits\Query;

class Menu extends Model implements Query
{
    use PhambinhModel;

    protected $table = 'menus';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'slug',
    ];

     /**
     * Các tham số được phép truyền vào từ URL
     *
     * @var array
     */
    protected static $requestFilter = [
        
    ];

    /**
     * Giá trị mặc định của các tham số
     *
     * @var array
     */
    protected static $defaultOfQuery = [
        'orderby'      =>  'id.desc',
    ];

    public function items()
    {
        return $this->hasMany('Phambinh\Appearance\Models\MenuItem');
    }

    public function scopeOfQuery($query, $args = [])
    {
        $query->baseQuery($args);
    }

    public function scopeUpdateStruct($query, $menu_items, $parent_id = '0')
    {
        foreach ($menu_items as $menu_item) {
            if (isset($menu_item['children'])) {
                $query->updateStruct($menu_item['children'], $menu_item['id']);
            }
        }

        $this->items()->whereIn('id', array_pluck($menu_items, 'id'))->update(['parent_id' => $parent_id]);
    }
}
