<div class="form-group field-blogpost-badge_constructor ">
    <label class="control-label col-sm-2" for="blogpost-badge"><?= $label ?></label>
    <div class="col-sm-8">
        <label id="badge_demo">
            <?= $badge ?>
            <?= !empty($badge) ? '<span class="h6 text-danger badge-delete" data-badge-delete>(Удалить плашку)</span>' : '' ?>
        </label>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-secondary" data-badge-icon>
                <input type="radio" name="options" id="option1" autocomplete="off" >
                <img src="/upload/img/item_icons/check_green.svg"/>
            </label>
            <label class="btn btn-secondary" data-badge-icon>
                <input type="radio" name="options" id="option2" autocomplete="off" >
                <img src="/upload/img/item_icons/check_yellow.svg"/>
            </label>
            <label class="btn btn-secondary" data-badge-icon>
                <input type="radio" name="options" id="option3" autocomplete="off" >
                <img src="/upload/img/item_icons/geo_red.svg"/>
            </label>
            <label class="btn btn-secondary" data-badge-icon>
                <input type="radio" name="options" id="option4" autocomplete="off" >
                <img src="/upload/img/item_icons/kitchen_purple.svg"/>
            </label>
        </div>
        <input type="text" id="blogpost-badge" class="form-control" data-badge-text>
        <p class="help-block help-block-error "></p>
    </div>

</div>


<style>
    .item_present{
        font-family: Lato,sans-serif;
        -webkit-font-smoothing: antialiased;
        -webkit-tap-highlight-color: transparent;
        color: #333;
        -webkit-text-size-adjust: 100%;
        margin: 0;
        border: 0;
        vertical-align: baseline;
        box-sizing: border-box;
        display: flex;
        column-gap: 6px;
        background: #fff;
        border-radius: 2px;
        padding: 0 8px 0 7px;
        align-items: center;
        font-weight: 400;
        font-size: 11px;
        line-height: 20px;
    }

    .badge-delete{
        cursor: pointer;
    }

    #badge_demo{
        display: flex;
        gap: 0 10px;
    }

    .field-blogpost-badge_constructor{
        margin-top: -50px;
    }
</style>