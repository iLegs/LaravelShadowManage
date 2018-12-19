<form id="pagerForm" onsubmit="return navTabSearch(this);" method="get">
    <input type="hidden" name="pageNum" value="1"/>
</form>
<div class="pageHeader">
    <form onsubmit="return navTabSearch(this);" method="get">
        <div class="searchBar">
            <ul class="searchContent"></ul>
            <div class="subBar"></div>
        </div>
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <li>
                <a class="delete" href="/shadow/photoes/change/horizontal" target="selectedTodo" title="确定要改变这些图片吗?" warn="请选择至少一条记录" rel="ids[]" fresh="true">
                    <span>改变图片属性(横向)</span>
                </a>
            </li>
            <li>
                <a class="delete" href="/shadow/photoes/change/vertical" target="selectedTodo" title="确定要改变这些图片吗?" warn="请选择至少一条记录" rel="ids[]" fresh="true">
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
                        <img src="{{ $photo->getUrl() }}">
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
        <div class="pagination" targetType="navTab" totalCount="{{ $totalCount }}" numPerPage="{{ $pageSize }}" pageNumShown="5" currentPage="{{ $currentPage }}"></div>
    </div>
</div>
