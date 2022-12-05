{extends file="page.tpl"}

{block name='page_content_container'}
    {if $array_product == null}
        <h1> No products to compare </h1>
    {else}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            {foreach from=$array_product item=$p key=$k}
                <tr>
                    <td>{$k}</td>
                    <td>{$p->name}</td>
                    <td>{round($p->price,2)} zł</td>
                    {if $value_difference[$k] == 0}
                    <td></td>
                    {elseif $value_difference[$k] > 0}
                    <td style="color:red">+{round($value_difference[$k],1)}%</td>
                    {else}
                    <td style="color:green">{round($value_difference[$k],1)}%</td>
                    {/if}
                    <td>{include file="module:compare/views/templates/hook/delete_button.tpl" key=$k url=$url}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}
{/block}