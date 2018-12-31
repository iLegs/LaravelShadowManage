<form id="pagerForm" onsubmit="return navTabSearch(this);" method="get">
    <input type="hidden" name="pageNum" value="{{ $pageNum }}"/>
    <input type="hidden" name="type" value="{{ $type }}"/>
    <input type="hidden" name="albumid" value="{{ $albumid }}"/>
</form>
<div class="pageHeader">
    <form onsubmit="return navTabSearch(this);" method="get">
        <div class="searchBar">
            <table class="searchContent">
            <tr>
                <td>
                    <label>请选择专辑</label>
                    <select class="combox" name="albumid">
                        <option value="0">全部</option>
                        @foreach($albums as $album)
                            <option value="{{ $album->id }}" @if($album->id == $albumid) selected="selected" @endif>{{ $album->title }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <label>请选择照片类型</label>
                    <select class="combox" name="type">
                        <option value="-1">全部</option>
                        <option value="0" @if($type == 0) selected="selected" @endif>竖图</option>
                        <option value="1" @if($type == 1) selected="selected" @endif>横图</option>
                    </select>
                    <input type="hidden" name="pageNum" value="{{ $pageNum }}"/>
                </td>
            </tr>
        </table>
            <div class="subBar">
                <ul>
                    <li>
                        <div class="buttonActive">
                            <div class="buttonContent">
                                <button type="submit">检索</button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <li>
                <a class="delete changew" href="/shadow/photoes/change/horizontal" target="selectedTodo" title="确定要改变这些图片吗?" warn="请选择至少一条记录" rel="ids[]" fresh="true">
                    <span>改变图片属性(横向)</span>
                </a>
            </li>
            <li>
                <a class="delete changeh" href="/shadow/photoes/change/vertical" target="selectedTodo" title="确定要改变这些图片吗?" warn="请选择至少一条记录" rel="ids[]" fresh="true">
                    <span>改变图片属性(竖向)</span>
                </a>
            </li>
            <li>
                <a class="delete" href="/shadow/photoes/delete" target="selectedTodo" title="确定要删除这些记录吗?" warn="请选择至少一条记录" rel="ids[]" fresh="true">
                    <span>批量删除图片</span>
                </a>
            </li>
        </ul>
    </div>
    <table class="table" width="100%" layoutH="138">
        <thead>
            <tr>
                <th width="20" align="center">
                    <div class="gridCol">
                        <input type="checkbox" group="ids[]" class="checkboxCtrl">
                    </div>
                </th>
                <th width="40" align="center">编号</th>
                <th width="40" align="center">所属专辑</th>
                <th width="80" align="center">类型</th>
                <th width="160" align="center">图片</th>
                <th width="80" align="center">七牛key</th>
                <th width="80" align="center">新增时间</th>
            </tr>
        </thead>
        <tbody>
            @foreach($photoes as $photo)
                <tr target="pid" rel="{{ $photo->id }}">
                    <td>
                        <div><input name="ids[]" value="{{ $photo->id }}" type="checkbox"></div>
                    </td>
                    <td>{{ $photo->id }}</td>
                    <td>{{ $photo->album->title }}</td>
                    <td>
                        @if($photo->type == 0)
                            竖
                        @else
                            横
                        @endif
                    </td>
                    <td>
                        <img src="{{ $photo->getUrl()['preview'] }}">
                    </td>
                    <td>
                        <a target="_blank" href="{{ $photo->getUrl()['original'] }}" download="{{ $photo->qn_key }}">{{ $photo->qn_key }}</a>
                    </td>
                    <td>{{ $photo->add_time }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>共&nbsp;{{ $totalCount }}&nbsp;条，显示&nbsp;{{ $currentCount }}&nbsp;条</span>
        </div>
        <div class="pagination" targetType="navTab" totalCount="{{ $totalCount }}" numPerPage="{{ $pageSize }}" currentPage="{{ $currentPage }}"></div>
    </div>
</div>
<script src="{{ $s }}/dwz/js/jquery-2.1.4.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function(){
        var type = 0;

        $(".gridTbody img").each(function(){
            var w = $(this).width();
            var h = $(this).height();
            var tr = $(this).parent().parent().parent();
            if (type == 0 && w > h) {
                $(tr).find("input[name='ids[]']").attr('checked', 'checked');
            } else if (type == 1 && w < h) {
                $(tr).find("input[name='ids[]']").attr('checked', 'checked');
            }
        });
    });
</script>
