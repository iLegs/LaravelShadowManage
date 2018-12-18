<div class="pageContent">
    <form method="post" action="/shadow/legmodels/edit" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
        <div class="pageFormContent">
            <p>
                <label>请输入模特名称：</label>
                <input name="name" class="required" value="{{ $legmodel->name }}" type="text" size="30" alt="请输入标签名称"/>
            </p>
            <fieldset>
                <dl class="nowrap">
                    <dt>请输入模特简介：</dt>
                    <dd><textarea name="desc" cols="28" rows="20" class="textInput">{{ $legmodel->desc }}</textarea></dd>
                </dl>
            </fieldset>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="lmid" value="{{ $legmodel->id }}">
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
