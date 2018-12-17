<div class="pageContent">
    <form method="post" action="/shadow/tags/edit" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
        <div class="pageFormContent">
            <p>
                <label>请输入标签名称：</label>
                <input name="title" class="required" type="text" value="{{ $tag->title }}" size="30" alt="请输入标签名称"/>
                <input type="hidden" name="tid" value="{{ $tag->id }}">
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
