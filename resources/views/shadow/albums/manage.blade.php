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
            <li><a class="add" href="/shadow/albums/add" target="navTab" rel="add_albums"><span>添加专辑</span></a></li>
            <li>
                <a class="delete" href="/shadow/albums/delete" target="selectedTodo" title="确定要删除这些记录吗?" warn="请选择至少一条记录" rel="ids[]"><span>批量删除专辑</span></a>
            </li>
        </ul>
    </div>
    <table class="table" width="100%" layoutH="138">
        <thead>
            <tr>
                <th width="20" align="center">
                    <div class="gridCol"><input type="checkbox" group="ids[]" class="checkboxCtrl"></div>
                </th>
                <th width="40" align="center">编号</th>
                <th width="80" align="center">名称</th>
                <th width="80" align="center">描述</th>
                <th width="80" align="center">新增时间</th>
                <th width="40" align="center">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($albums as $album)
                <tr target="pid" rel="{{ $album->id }}">
                    <td>
                        <div><input name="ids[]" value="{{ $album->id }}" type="checkbox"></div>
                    </td>
                    <td>{{ $album->id }}</td>
                    <td>{{ $album->title }}</td>
                    <td>{{ $album->desc }}</td>
                    <td>{{ $album->add_time }}</td>
                    <td>
                        <div>
                            <a title="编辑" target="dialog" href="/shadow/albums/edit?libid={{ $album->id }}" class="btnEdit" mask="true" minable="false" maxable="false" resizable="false" drawable="false" width="400" height="458">编辑</a>
                        </div>
                    </td>
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
