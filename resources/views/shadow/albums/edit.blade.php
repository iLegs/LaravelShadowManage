<div class="pageContent">
    <form method="post" action="/shadow/albums/edit" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
        <div class="pageFormContent">
            <p>
                <label>请输入专辑名称：</label>
                <input name="title" class="required" value="{{ $album->title }}" type="text" size="30" alt="请输入专辑名称"/>
            </p>
            <fieldset>
                <dl class="nowrap">
                    <dt>请输入简介：</dt>
                    <dd><textarea name="desc" cols="160" rows="4" class="textInput">{{ $album->desc }}</textarea></dd>
                </dl>
            </fieldset>
            <h2 class="contentTitle">请选择专辑标签</h2>
            <div class="pageFormContent tag">
                <?php $a_tags = array_column($album->getTags(), 'id');?>
                @foreach($tags as $tag)
                    <label><input type="checkbox" name="tag[]" value="{{ $tag->id }}" @if(in_array($tag->id, $a_tags)) checked="checked" @endif>{{ $tag->title }}</label>
                @endforeach
            </div>
            <h2 class="contentTitle">请选择专辑模特</h2>
            <div class="pageFormContent mdl">
                <?php $a_models = array_column($album->getModels(), 'id');?>
                @foreach($legmodels as $legmodel)
                    <label><input type="checkbox" name="mdl[]" value="{{ $legmodel->id }}" @if(in_array($legmodel->id, $a_models)) checked="checked" @endif>{{ $legmodel->name }}</label>
                @endforeach
            </div>
            <p>
                <label>请选择图库：</label>
                <select class="combox" name="lib" ref="lib" class="required">
                    @foreach($libs as $lib)
                        <option value="{{ $lib->id }}" @if($lib->id == $album->lib_id) selected="selected" @endif>{{ $lib->title }}</option>
                    @endforeach
                </select>
            </p>
            <p>
                <label>请选择发行日期：</label>
                <input type="text" name="date" class="date textInput readonly valid required" dateFmt="yyyy-MM-dd" readonly="true" maxDate="{{ $next_day }}" value="{{ $album->date }}" />
                <a class="inputDateButton" href="javascript:;">选择</a>
            </p>
            <p>
                <label>请选择专辑状态：</label>
                @if($album->status == 0)
                    <input type="radio" name="status" value="0" @if($album->status == 0) checked="checked" @endif>待上线&nbsp;&nbsp;&nbsp;&nbsp;
                @endif
                <input type="radio" name="status" value="1" @if($album->status == 1) checked="checked" @endif>已上线&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="status" value="2" @if($album->status == 2) checked="checked" @endif>已下线
            </p>
            <input type="hidden" name="aid" value="{{ $album->id }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </div>
        <div class="formBar">
            <ul>
                <li>
                    <div class="buttonActive">
                        <div class="buttonContent">
                            <button type="submit">提交</button>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="button">
                        <div class="buttonContent">
                            <button type="button" class="close">取消</button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>
