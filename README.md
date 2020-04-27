laravel-admin extension
======
##使用:

```php
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;

Class SomeModel extends Model implements Sortable
{
    use \Weiwait\Sorting\SortingTrait;
    $sortable = [
        'direciton' => 'asc or desc', // 设定升序降序
        'restriction' => 'this field or unset', // 设定排序时根据此字段分组，排序将根据此字限定段累加 （query->where($restriction, this->$restriction)）
    ];
}
```

### Grid:
```php
public function grid()
{
    $grid->model()->orderBy('order');
    $grid->column('field', 'label')->sorting();
}
```
