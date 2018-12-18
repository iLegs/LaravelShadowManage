<div class="pageContent">
    <form method="post" action="/shadow/albums/add" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
        <div class="pageFormContent">
            <p>
                <label>请输入专辑名称：</label>
                <input name="title" class="required" type="text" size="30" alt="请输入专辑名称"/>
            </p>
            <fieldset>
                <dl class="nowrap">
                    <dt>请输入简介：</dt>
                    <dd><textarea name="desc" cols="160" rows="4" class="textInput"></textarea></dd>
                </dl>
            </fieldset>
            <h2 class="contentTitle">请选择专辑标签</h2>
            <div class="pageFormContent tag">
                @foreach($tags as $tag)
                    <label><input type="checkbox" name="tag[]" value="{{ $tag->id }}">{{ $tag->title }}</label>
                @endforeach
            </div>
            <h2 class="contentTitle">请选择专辑模特</h2>
            <div class="pageFormContent tag">
                @foreach($legmodels as $legmodel)
                    <label><input type="checkbox" name="mdl[]" value="{{ $legmodel->id }}">{{ $legmodel->name }}</label>
                @endforeach
            </div>
            <p>
                <label>请选择图库：</label>
                <select class="combox" name="lib" ref="lib" class="required">
                    @foreach($libs as $lib)
                        <option value="{{ $lib->id }}">{{ $lib->title }}</option>
                    @endforeach
                </select>
            </p>
            <p>
                <label>请选择发行日期：</label>
                <input type="text" name="date" class="date textInput readonly valid required" dateFmt="yyyy-MM-dd" readonly="true" maxDate="{{ $next_day }}"/>
                <a class="inputDateButton" href="javascript:;">选择</a>
            </p>
            <p>
                <label>请输入前缀：</label>
                <input name="prefix" class="required" type="text" size="30" alt="请输入前缀"/>
            </p>
            <p>
                <label>请输入结束：</label>
                <input name="end" class="required" type="text" size="30" alt="请输入结束"/>
            </p>
            <p>
                <label>请输入后缀：</label>
                <input name="postfix" class="required" type="text" size="30" alt="请输入后缀"/>
            </p>
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
