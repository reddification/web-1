<p>
    <span class="label label-default"> А ещё можешь подписаться на вот этих уродов: </span>
    <span class="label label-primary"><?= count($twitterUser->advices) ?> </span>
</p>
<hr/>

<?php foreach($twitterUser->advices as $advice):?>
    <div class="twitter_user">
        <img class="img-responsive img-rounded"
             src="<?= str_replace("_normal.",".",$advice["profile_image_url"]) ?>" />
        <a href="https://twitter.com/<?=$advice["screen_name"] ?>" target="_blank"> <span class="label label-default"><?= $advice["screen_name"]?></span> </a>

        <button type="button" class="btn btn-primary" data-toggle="tooltip" title="дизлайк атписка"
                onclick="subscribe_faggot(<?= $advice["id_str"]?>,'<?=$advice["screen_name"]?>')" id="bitch_<?=$advice["screen_name"]?>">
            падписацца
        </button>

        <blockquote>
            <p><?= $advice["description"]?></p>
            <footer><?=$advice["screen_name"]?></footer>
        </blockquote>
    </div>
<?php endforeach; ?>
