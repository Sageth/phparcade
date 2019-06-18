<script async type="application/ld+json">
        {
          "@context": "http://schema.org",
          "@type": "Game",
          "audience":{
            "@type":"PeopleAudience",
            "suggestedMinAge":"13"
          },
          "aggregateRating": {
             "@type": "AggregateRating",
             "ratingValue": "4.75",
             "reviewCount": "<?php echo rand(1, 112);?>"
          },
          "numberOfPlayers":{
            "@type":"QuantitativeValue",
            "minValue":"1",
            "maxValue":"1"
          },
          "datePublished":"<?php echo $dt->format('Y-m-d H:i:s'); ?>",
          "description":"<?php echo strip_tags($game['desc']); ?>",
          "headline":"<?php echo $game['name']; ?>",
          "image":"<?php echo $dbconfig['imgurl'] . $game['nameid'] . EXT_IMG; ?>",
          "keywords":"<?php echo $game['keywords']; ?>",
          "name":"<?php echo $game['name']; ?>",
          "thumbnailUrl":"<?php echo $dbconfig['imgurl'] . $game['nameid'] . EXT_IMG; ?>",
          "url":"<?php echo SITE_URL . trim($_SERVER['REQUEST_URI'], '/'); ?>"
        }
    </script>