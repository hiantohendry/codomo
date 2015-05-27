@include('include.header')
<div class="workspace">
    <div class="board">This is Board


    {{ Form::open(array('url'=>'upload','method'=>'POST', 'files'=>true)) }}

    {{ Form::file('photo') }}
    {{ Form::submit('Submit', array('class'=>'send-btn')) }}
    {{ Form::close() }}
    </div>
    <div class="sidebar">
        <div class="action">
            <button class="btn action-button" title="Open Page"><i class="fa fa-folder-open-o"></i></button>
            <button class="btn action-button" title="Save"><i class="fa fa-floppy-o"></i></button>
            <button class="btn action-button" title="Publish"><i class="fa fa-link"></i></button>
        </div>
        <div id="js-tools" class="tools-wrapper">
            <div class="title">Tools</div>
            <div class="tools">
                <button class="btn action-button" title="Insert Text"><i class="fa fa-font"></i></button>
                <button class="btn action-button" title="Insert Image"><i class="fa fa-file-image-o"></i></button>
            </div>
        </div>
        <div id="js-properties" class="properties-wrapper">
            <div class="title">Properties</div>
            <div class="properties">
                <div id="prop-name">
                    <span>Item 1</span>
                    <button id="lock-button" class="btn action-button small-button" title="Lock / Unlock">
                        <i class="fa fa-unlock"></i>
                        <i class="fa fa-lock"></i>
                    </button>
                </div>
                <div id="prop-dimension">
                    <div>
                        Width
                        <input type="number">
                    </div>
                    <div>
                        &times;
                    </div>
                    <div>
                        Height
                        <input type="number">
                    </div>
                </div>
                <div id="padding">
                    <label>Padding</label>
                    <table>
                        <tr>
                            <td></td>
                            <td><input type="number"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><input type="number"></td>
                            <td></td>
                            <td><input type="number"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="number"></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div id="border">
                    <label>Border</label>
                    <table>
                        <tr>
                            <td></td>
                            <td><input type="number"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><input type="number"></td>
                            <td></td>
                            <td><input type="number"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="number"></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('include.footer')