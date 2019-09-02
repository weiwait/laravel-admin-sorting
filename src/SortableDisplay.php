<?php

namespace Weiwait\Sorting;

use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Column;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;

class SortableDisplay extends AbstractDisplayer
{
    protected $index;
    protected $page;
    protected $perPage;
    protected $serial;

    public function __construct($value, Grid $grid, Column $column, $row)
    {
        parent::__construct($value, $grid, $column, $row);

        $this->detectorIndex()->detectorPerPage()->detectorPage()->resolveSerial();
    }

    protected function script()
    {
        $class = $this->grid->model()->getOriginalModel()->getMorphClass();
        $class = str_replace('\\', '\\\\', $class);

        return /** @lang JavaScript */ <<<SCRITP
            $('.order-to').on('click', function () {
                let id = $(this).data('id');
                let order = orderNumber(id);
                let model = "{$class}";
                
                sorting(id, model, order, 'to');
            });
            $('.order-ascend').on('click', function () {
                let id = $(this).data('id');
                let model = "{$class}";
                
                sorting(id, model, 1, 'ascend');
            });
            $('.order-descend').on('click', function () {
                let id = $(this).data('id');
                let model = "{$class}";
                
                sorting(id, model, 1, 'descend');
            });
            $('.order-ascend-specified').on('click', function () {
                let id = $(this).data('id');
                let order = orderNumber(id);
                let model = "{$class}";
                
                sorting(id, model, order, 'ascend');
            });
            $('.order-descend-specified').on('click', function () {
                let id = $(this).data('id');
                let order = orderNumber(id);
                let model = "{$class}";
                
                sorting(id, model, order, 'descend');
            });

            
            
            function orderNumber(id) {
                let orderNumber = $("input[data-sorting-id=" + id +"]").val();
                
                let re = /[0-9]/;
                
                if (!re.test(orderNumber)) {
                    swal('数值错误', '请输入正确的数字', 'warning')
                    return false;
                }
                
                return orderNumber;
            }
            
            function sorting(id, model, order, action) {
                $.ajax({
                    type: 'PUT',
                    url: '/weiwait/sorting',
                    data: {
                        id: id, model: model, order: order, action: action
                    },
                    success: d => {
                        toastr.success(d);
                        $.admin.reload();
                    },
                    error: d => {
                        swal('排序失败', d.message, 'error')
                    }
                })
            }
SCRITP;
    }

    public function display()
    {
        Admin::script($this->script());

        return /** @lang HTML */ <<<EOT
            <div id="sorting">
                <div class="btn-group-vertical" style="width: 2em; float: left;">
                    <button data-id="{$this->row->id}" class="btn btn-xs btn-info btn-block order-ascend" style="height: 2.43em;">
                        <i class="fa fa-caret-up fa-fw"></i>
                    </button>
                    <button data-id="{$this->row->id}" class="btn btn-xs btn-default btn-block order-descend" style="height: 2.43em;">
                        <i class="fa fa-caret-down fa-fw"></i>
                    </button>
                </div>
                <div class="btn-group-vertical btn-group" style="float: left; width: 3.2em;">
                    <button data-id="{$this->row->id}" class="btn btn-xs btn-info btn-block order-ascend-specified" style="height: 1.5em;" id="order-add">
                        <i class="fa fa-caret-up fa-fw"></i>
                    </button>
                    <input data-sorting-id="{$this->row->id}" type="number" pattern="[0-9]" value="{$this->serial}" style="height: 1.5em; border: none; outline: none; width: 3.2em; text-align: center;">
                    <button data-id="{$this->row->id}" class="btn btn-xs btn-default btn-block order-descend-specified" style="height: 1.5em;">
                        <i class="fa fa-caret-down fa-fw"></i>
                    </button>
                </div>
<!--                <div class="btn-group-vertical btn-group" style="float: left; width: 2em;">-->
<!--                    <button class="btn btn-xs btn-default btn-block" style="height: 2em;">-->
<!--                        <i class="fa fa-caret-down fa-fw"></i>-->
<!--                    </button>-->
<!--                    <button class="btn btn-xs btn-default btn-block" style="height: 2.86em;">-->
<!--                        <i class="fa fa-caret-down fa-fw"></i>-->
<!--                    </button>-->
<!--                </div>-->
                <div class="btn-group-vertical btn-group" style="float: left; width: 2.4em;">
                    <button class="btn btn-xs btn-default btn-block order-to" data-id="{$this->row->id}" style="height: 4.76em;">
                        <i class="fa fa-flash fa-fw"></i>
                    </button>
                </div>
            </div>
EOT;
    }

    protected function detectorPage()
    {
        $this->page = request('page', 1);
        return $this;
    }

    protected function detectorPerPage()
    {
        $this->perPage = $this->grid->model()->getPerPage();
        return $this;
    }

    protected function detectorIndex()
    {
        $this->grid->model()->buildData()->each(function ($item, $key) {
            if ($item->id == $this->row->id)
                $this->index = $key;
        });

        return $this;
    }

    protected function resolveSerial()
    {
        $this->serial = ($this->page - 1) * $this->perPage + $this->index + 1;
        return $this;
    }
}
