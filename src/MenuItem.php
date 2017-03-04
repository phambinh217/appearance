<?php

namespace Phambinh\Appearance;

use Illuminate\Database\Eloquent\Model;
use Phambinh\Cms\Support\Traits\Model as PhambinhModel;
use Phambinh\Cms\Support\Traits\Query;

class MenuItem extends Model implements Query
{
    use PhambinhModel;

    protected $table = 'menu_items';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'url',
        'menu_id',
        'parent_id',
        'object_id',
        'type',
        'icon',
        'css_class',
        'order',
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

    public function menu()
    {
        return $this->belongTo('Phambinh\Appearance\Menu');
    }

    public function scopeOfQuery($query, $args = [])
    {
        $args = $this->defaultParams($args);
        $query->baseQuery($args);
    }

    public function hasChild()
    {
        return $this->ofQuery(['parent_id'], $this->id)->count() != 0;
    }

    public function scopeOfParentAble($query)
    {
        $query->where('id', '!=', $this->id)->where('parent_id', '!=', $this->id);
    }
}
