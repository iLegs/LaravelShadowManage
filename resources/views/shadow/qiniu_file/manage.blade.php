<form id="pagerForm" onsubmit="return navTabSearch(this);" method="get">
</form>
<div class="pageHeader">
    <form onsubmit="return navTabSearch(this);" method="get">
        <div class="searchBar">
            <table class="searchContent">
            <tr>
                <td>
                    <label>请输入前缀</label>
                    <input type="text" name="keywords" value="{{ $keywords }}">
                    <input type="hidden" name="marker" value="{{ $marker }}">
                </td>
                <td>
                    <label>请选择输出结果</label>
                    <select class="combox" name="type">
                        <option value="0" @if($type == 0) selected="selected" @endif>列出所有文件</option>
                        <option value="1" @if($type == 1) selected="selected" @endif>计算最后一张</option>
                    </select>
                </td>
                <td>
                    <label>结果条数：{{ $total_count }}</label>
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
    </div>
    <table class="table" width="100%" layoutH="138">
        <thead>
            <tr>
                <th width="40" align="left">七牛云key</th>
            </tr>
        </thead>
            @foreach($rows as $vv)
                <tr>
                    <td>{{ $vv }}</td>
                </tr>
            @endforeach
        <tbody>
        </tbody>
    </table>
</div>
