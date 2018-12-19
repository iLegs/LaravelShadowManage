<form id="pagerForm" onsubmit="return navTabSearch(this);" method="get">
    <input type="hidden" name="pageNum" value="1"/>
    <input type="hidden" name="year" value="{{ $yearid }}"/>
    <input type="hidden" name="lib" value="{{ $libid }}"/>
</form>
<div class="pageHeader">
    <form onsubmit="return navTabSearch(this);" method="get">
        <div class="searchBar">
            <ul class="searchContent">
                <li>
                    <label>请选择套图</label>
                    <select class="combox" name="lib">
                        <option value="0" selected="selected">全部</option>
                        @foreach($libs as $lib)
                            <option value="{{ $lib->id }}" @if($libid == $lib->id) selected="selected" @endif>{{ $lib->title }}</option>
                        @endforeach
                    </select>
                </li>
                <li>
                    <label>发行年份</label>
                    <select class="combox" name="year">
                        <option value="0" selected="selected">全部</option>
                        <option value="2010" @if('2010' == $yearid)selected="selected"@endif>2010</option>
                        <option value="2011" @if('2011' == $yearid)selected="selected"@endif>2011</option>
                        <option value="2012" @if('2012' == $yearid)selected="selected"@endif>2012</option>
                        <option value="2013" @if('2013' == $yearid)selected="selected"@endif>2013</option>
                        <option value="2014" @if('2014' == $yearid)selected="selected"@endif>2014</option>
                        <option value="2015" @if('2015' == $yearid)selected="selected"@endif>2015</option>
                        <option value="2016" @if('2016' == $yearid)selected="selected"@endif>2016</option>
                        <option value="2017" @if('2017' == $yearid)selected="selected"@endif>2017</option>
                        <option value="2018" @if('2018' == $yearid)selected="selected"@endif>2018</option>
                        <option value="2019" @if('2019' == $yearid)selected="selected"@endif>2019</option>
                    </select>
                </li>
            </ul>
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
                <a class="add" href="/shadow/albums/add" target="navTab" rel="add_albums" fresh="true">
                    <span>添加专辑</span>
                </a>
            </li>
            <li>
                <a class="add" href="/shadow/albums/upload?aid={aid}" target="dialog" rel="cover_upload" minable="false" maxable="false" resizable="false" drawable="false" mask="true" width="280" height="190" fresh="true">
                    <span>上传封面</span>
                </a>
            </li>
            <li>
                <a class="delete" href="/shadow/albums/delete" target="selectedTodo" title="确定要删除这些记录吗?" warn="请选择至少一条记录" rel="ids[]" fresh="true">
                    <span>批量删除专辑</span>
                </a>
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
                <th width="80" align="center">模特</th>
                <th width="80" align="center">标签</th>
                <th width="80" align="center">封面</th>
                <th width="40" align="center">发行日期</th>
                <th width="40" align="center">所属套图</th>
                <th width="40" align="center">状态</th>
                <th width="80" align="center">新增时间</th>
                <th width="40" align="center">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($albums as $album)
                <tr target="aid" rel="{{ $album->id }}">
                    <td>
                        <input name="ids[]" value="{{ $album->id }}" type="checkbox">
                    </td>
                    <td>{{ $album->id }}</td>
                    <td>{{ $album->title }}</td>
                    <td>{{ $album->desc }}</td>
                    <td>
                        @foreach($album->getModels() as $model)
                            {{ $model['name'] }}
                        @endforeach
                    </td>
                    <td>
                        @foreach($album->getTags() as $tag)
                            <span>{{ $tag['title'] }}</span>
                        @endforeach
                    </td>
                    <td height="80">
                        <a href="{{ $album->getCover()['original'] }}" target="_blank">
                            <img src="{{ $album->getCover()['preview'] }}" style="width: 45px;height: 80px;border-radius: 10px;padding: 5px;">
                        </a>
                    </td>
                    <td>
                        {{ $album->date }}
                    </td>
                    <td>
                        {{ $album->lib->title }}
                    </td>
                    <td>
                        @if($album->status == 0)
                            待上线
                        @elseif($album->status == 1)
                            已上线
                        @elseif($album->status == 2)
                            已删除
                        @endif
                    </td>
                    <td>{{ $album->add_time }}</td>
                    <td>
                        <div>
                            <a title="编辑专辑" target="navTab" rel="edit_albums" href="/shadow/albums/edit?aid={{ $album->id }}" class="btnEdit" fresh="true">编辑专辑</a>
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
