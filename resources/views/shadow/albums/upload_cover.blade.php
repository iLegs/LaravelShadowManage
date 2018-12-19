<div class="pageContent">
    <form method="post" action="/shadow/resource/upload" enctype="multipart/form-data" class="pageForm required-validate" onsubmit="return iframeCallback(this, dialogAjaxDone);">
        <div class="pageFormContent">
            <div class="unit">
                <label>上传单个文件：</label>
                <div class="upload-wrap">
                    <input type="file" name="resource" accept="image/*">
                    <input type="hidden" name="resource_type" value="0">
                    <input type="hidden" name="aid" value="{{ $album->id }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </div>
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
