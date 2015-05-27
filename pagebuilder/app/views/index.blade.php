@include('include.header')

<div class="workspace">
    <div class="board">
		<div class="page" id="Page">
			<div id="text-editor"></div>
		</div>
	</div>
    <div class="sidebar">
        <div class="action">
            <button class="buttn action-button modal-toggle" title="New Page" target="modal-new"><i class="fa fa-file-o"></i></button>
            <button class="buttn action-button modal-toggle" title="Open Page" onclick="listPage()"><i class="fa fa-folder-open-o"></i></button>
            <button class="buttn action-button modal-toggle" title="Save" target="modal-save"><i class="fa fa-floppy-o"></i></button>
            <button class="buttn action-button modal-toggle" title="Publish" target="modal-publish"><i class="fa fa-link"></i></button>
        </div>
        <div id="js-tools" class="tools-wrapper">
            <div class="title">Tools</div>
            <div class="tools">
                <button class="buttn action-button" title="Insert Text" onclick="addItem('text')"><i class="fa fa-font"></i></button>
                <button class="buttn action-button" title="Insert Image" onclick="addImage()"><i class="fa fa-file-image-o"></i></button>
            </div>
        </div>
        <div id="js-properties" class="properties-wrapper">
            <div class="title">Properties</div>
            <div class="properties">
                <div id="prop-name">
                    <span id="item-id"></span>
					<input type="checkbox" id="lock" />
                    <button id="lock-button" class="buttn action-button small-button" title="Lock / Unlock"><i class="fa fa-unlock"></i><i class="fa fa-lock"></i></button>
                    <button id="trash-button" class="buttn action-button small-button modal-toggle" title="Delete" target="modal-delete">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <div id="prop-dimension">
                    <div>
                        Width
                        <input class="input-default" type="number" id="width" data-css="width">
                    </div>
                    <div>
                        &times;
                    </div>
                    <div>
                        Height
                        <input class="input-default" type="number" id="height" data-css="height">
                    </div>
                </div>
                <div id="padding">
                    <label>Padding</label>
                    <table>
                        <tr>
                            <td>Top</td>
                            <td><input class="input-default" type="number" id="pad-top" data-css="padding-top"></td>
                        </tr>
                        <tr>
                            <td>Bottom</td>
                            <td><input class="input-default" type="number" id="pad-bottom" data-css="padding-bottom"></td>
                        </tr>
                        <tr>
                            <td>Right</td>
                            <td><input class="input-default" type="number" id="pad-right" data-css="padding-right"></td>
                        </tr>
                        <tr>
                            <td>Left</td>
                            <td><input class="input-default" type="number" id="pad-left" data-css="padding-left"></td>
                        </tr>
                    </table>
                </div>
                <div id="border">
                    <label>
                        Border 
                        <div><input type="color" id="border-color" data-css="border-color"></div>
                    </label>
                    <table>
                        <tr>
                            <td>Top</td>
                            <td><input class="input-default" type="number" id="border-top" data-css="border-top-width"></td>
                        </tr>
                        <tr>
                            <td>Bottom</td>
                            <td><input class="input-default" type="number" id="border-bottom" data-css="border-bottom-width"></td>
                        </tr>
                        <tr>
                            <td>Right</td>
                            <td><input class="input-default" type="number" id="border-right" data-css="border-right-width"></td>
                        </tr>
                        <tr>
                            <td>Left</td>
                            <td><input class="input-default" type="number" id="border-left" data-css="border-left-width"></td>
                        </tr>
                    </table>
                </div>
                <div id="shadow">
                    <label><input class="input-default" type="checkbox" id="box-shadow" data-css="box-shadow"> Box Shadow</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay"></div>
<div class="texteditor-overlay"></div>
<div class="loading-overlay">
    <div class="loading">
        <div class="spinner">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
        <div id="loading-message">Loading...</div>
    </div>
</div>
<div id="modal-open" class="modal-wrapper">
    <div class="modal-hdr">
        Open Saved Pages
    </div>
    <div class="modal-close" data-toggle="modal-close">&times;</div>
    <div class="modal-cntnt">
        <ul class="modal-list" id="list-page">
            
        </ul>
    </div>
</div>

<div id="modal-new" class="modal-wrapper">
    <div class="modal-hdr">
        New Page
    </div>
    <div class="modal-close" data-toggle="modal-close">&times;</div>
    <div class="modal-cntnt">
        Insert name
        {{ Form::open(array('id' => 'form-new','url' => 'newpage', 'class'=>'','method' => 'post')) }}
        <div><input type="text" name="pagename" id="new-name" class="input-default"></div>
        <div class="dimension-info">Insert Dimension</div>
        <div class="dimension">
            <div>
                <label>Width:</label><input type="number" id="new-width" class="input-default">
            </div>
            <div>
                <label>Height:</label><input type="number" id="new-height" class="input-default">
            </div>
        </div>
        <div class="modal-button">
            <button class="link" data-toggle="modal-close" type="button">Cancel</button>
            <button class="buttn secondary-button new-button" type="button">Save</button>
        </div>
        {{ Form::close() }}
    </div>
</div>
<div id="modal-save" class="modal-wrapper">
    <div class="modal-hdr">
        Save page
    </div>
    <div class="modal-close" data-toggle="modal-close">&times;</div>
    <div class="modal-cntnt">
        Insert name
        <form action="#" id="form-save">
            <div><input type="text" id="save-name" class="input-default" name="page_name"></div>
            <div class="modal-button">
                <button class="link" data-toggle="modal-close" type="button">Cancel</button>
                <button class="buttn secondary-button" onclick="savePage()" type="button">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-publish" class="modal-wrapper">
    <div class="modal-hdr">
        Publish Link
    </div>
    <div class="modal-close" data-toggle="modal-close">&times;</div>
    <div class="modal-cntnt">
        You can publish this page using this link
        <blockquote>
            <span id="modal-link">hellopanda.com&asfasdfadsfadf</span>
        </blockquote>
    </div>
</div>

<div id="modal-delete" class="modal-wrapper">
    <div class="modal-hdr">
        Delete Confirmation
    </div>
    <div class="modal-close" data-toggle="modal-close">&times;</div>
    <div class="modal-cntnt">
        <div>Are you sure to delete this item?</div>
        <div class="modal-button">
            <button class="link" data-toggle="modal-close">No</button>
            <button class="buttn secondary-button" onclick="deleteItem()">Yes</button>
        </div>
    </div>
</div>

<div id="modal-info" class="modal-wrapper">
    <div class="modal-hdr" id="info-header"></div>
    <div class="modal-close" data-toggle="modal-close">&times;</div>
    <div class="modal-cntnt" id="info-content"></div>
</div>

<div id="modal-image-upload" class="modal-wrapper">
    <div class="modal-hdr">
        Upload your image
    </div>
    <div class="modal-close" data-toggle="modal-close">&times;</div>
    <div class="modal-cntnt">
        {{ Form::open(array('id' => 'form-upload','url' => 'upload', 'class'=>'','method' => 'post','files' => true)) }}
        File :    {{ Form::file('photo') }}
        <input type="hidden" id="image-id" name="id">
        <input type="hidden" id="image-name" name="name">
        <div><small>*Max image size is 1 MB</small></div>
        <div class="modal-button">
            <button class="link" data-toggle="modal-close" type="button">Cancel</button>
            <button class="buttn secondary-button upload-button" type="button">Upload</button>
        </div>
        {{ Form::close() }}
    </div>
</div>

@include('include.footer')