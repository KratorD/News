{* Purpose of this template: Display an edit form for configuring the newsletter plugin *}
{assign var='objectTypes' value=$plugin_parameters.MUNewsModule_NewsletterPlugin_ItemList.param.ObjectTypes}
{assign var='pageArgs' value=$plugin_parameters.MUNewsModule_NewsletterPlugin_ItemList.param.Args}

{assign var='j' value=1}
{foreach key='objectType' item='objectTypeData' from=$objectTypes}
    <hr />
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <div class="checkbox">
                <label>
                    <input id="plugin_{$i}_enable_{$j}" type="checkbox" name="MUNewsModuleObjectTypes[{$objectType}]" value="1"{if $objectTypeData.nwactive} checked="checked"{/if} /> {$objectTypeData.name|safetext}
                </label>
            </div>
        </div>
    </div>
    <div id="plugin_{$i}_suboption_{$j}">
        <div class="form-group">
            <label for="mUNewsModuleArgs_{$objectType}_sorting" class="col-sm-3 control-label">{gt text='Sorting'}:</label>
            <div class="col-sm-9">
                <select id="mUNewsModuleArgs_{$objectType}_sorting" name="MUNewsModuleArgs[{$objectType}][sorting]" class="form-control">
                    <option value="random"{if $pageArgs.$objectType.sorting eq 'random'} selected="selected"{/if}>{gt text='Random'}</option>
                    <option value="newest"{if $pageArgs.$objectType.sorting eq 'newest'} selected="selected"{/if}>{gt text='Newest'}</option>
                    <option value="alpha"{if $pageArgs.$objectType.sorting eq 'default' || ($pageArgs.$objectType.sorting != 'random' && $pageArgs.$objectType.sorting != 'newest')} selected="selected"{/if}>{gt text='Default'}</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="mUNewsModuleArgs_{$objectType}_amount" class="col-sm-3 control-label">{gt text='Amount'}:</label>
            <div class="col-sm-9">
                <input type="text" id="mUNewsModuleArgs_{$objectType}_amount" name="MUNewsModuleArgs[{$objectType}][amount]" value="{$pageArgs.$objectType.amount|default:'5'}" maxlength="2" size="10" class="form-control" />
            </div>
        </div>
        
        <div class="form-group">
            <label for="mUNewsModuleArgs_{$objectType}_filter" class="col-sm-3 control-label">{gt text='Filter (expert option)'}:</label>
            <div class="col-sm-9">
                <input type="text" id="mUNewsModuleArgs_{$objectType}_filter" name="MUNewsModuleArgs[{$objectType}][filter]" value="{$pageArgs.$objectType.filter|default:''}" size="40" class="form-control" />
            </div>
        </div>
    </div>
    {assign var='j' value=$j+1}
{foreachelse}
    <p class="alert alert-warning">{gt text='No object types found.'}</p>
{/foreach}
