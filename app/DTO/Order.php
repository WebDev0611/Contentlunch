<?php


namespace App\DTO;

/**
 * Class Order
 *
 * This is a Data Transfer Object that hold the data model for a writer access order since we are not
 * keeping the data in our database.
 * @package DTO
 */
class Order
{
    /**
     * The ID of a container project associated with this client account.
     * Alternatively can specify projectname parameter, where the projectid will be inferred or created on demand.
     * @var
     */
    private $projectid;

    /**
     * Number of hours available to the writer to submit their work, starting from the time of their checkout.
     * Values must be multiples of 24, up to 480 (20 days)
     * @var
     */
    private $hourstocomplete;

    /**
     * Writer level to post job at.
     * Writers at or above this level will be able to check the order out. Must be a value between 2 and 6.
     * @var
     */
    private $writer;

    /**
     * Minimum number of words to be submitted by writer.  Must be larger than 50.
     * @var
     */
    private $minwords;

    /**
     * Maximum number of words to be paid for by client.
     * Writer may submit more words but will not be paid for additional words.
     * Must be greater than or equal to minwords parameter value.
     * @var
     */
    private $maxwords;

    /**
     * The status of the order.
     * @var
     */
    private $status;


    /**
     * Title for this order, visible to writer.
     * @var
     */
    private $title;

    /**
     * The current textual content of the order.
     * @var
     */
    private $text;

    /**
     * Primary instructions for this order, visible to the writer.
     * Should unambiguously detail what is expected of the writer.  Optional if special parameter is specified.
     * @var
     */
    private $instructions;

    /**
     * The ID of a category to tag this order with.
     * Alternatively can specify categoryname parameter. Although optional, this field is recommended.
     * @var
     */
    private $categoryid;

    /**
     * The ID of an Asset Type to tag this order with. Although optional, this field is recommended.
     * @var
     */
    private $assetid;

    /**
     * The ID of an Expertise to tag this order with. Although optional, this field is recommended.
     * @var
     */
    private $expertiseid;

    /**
     * The number of hours to allow this order to remain in the writer pool.
     * If this time has elapsed the the order is in the pool or returns to the pool,
     * order will be automatically deleted.
     * @var
     */
    private $hourstoexpire;

    /**
     * The number or hours available to the client to approve an order, must be 72, 96, or 120.
     * @var
     */
    private $hourstoapprovejon;

    /**
     * The minimum number of hours available to the writer to resubmit an order if sent back for revisions,
     * must be at least 24 hours (default), and in increments of 12 hours beyond that.
     * @var
     */
    private $hourstorevise;

    /**
     * Editor level to post job at after writer has submitted their work and before client receives content.
     * @var
     */
    private $paidreview;

    /**
     * The maximum number of hours an editor will be able to charge for their review services.
     * Should be in quarter-hour increments, other values will be rounded up to nearest quarter-hour.
     * @var
     */
    private $maxhours;

    /**
     * Required SEO keywords, comma-delimited.  Writer must use each keyword phrase at least once.
     * @var
     */
    private $required;

    /**
     * Optional SEO keywords, comma-delimited. Writer sees this list but is under no obligation to use each phrase.
     * @var
     */
    private $optional;

    /**
     * Any special direction for how to integrate the required and optional keyword phrases.
     * @var
     */
    private $seo;

    /**
     * Special instructions for the writer, most commonly used when instructions contains boilerplate
     * instructions and this order has special needs in addition to those requirements.
     * @var
     */
    private $special;

    /**
     * Indicates whether the order should go only to the client’s Love List or ateam created by the client.
     * Must be 0 or 1, or correspond to the ID of a team retrieved from the List Teams method.
     * @var
     */
    private $lovelist;

    /**
     * The ID of a single writer to send this order to.  Overrides any value specified in lovelist parameter.
     * @var
     */
    private $targetwriter;

    /**
     * Indicate whether the writer should have a WYSIWYG editor, capable of HTML markup, or a plain text box.
     * @var
     */
    private $allowhtml;

    /**
     * The notes that will be submitted with the order to post to the comment stream at writer access.
     * @var
     */
    private $notes;

    /**
     * An additional dollar amountto add to the writer’s pay for this order.If specified, must not exceed available balance.
     * @var
     */
    private $bonus;

    /**
     * The client’s rating of the quality of the content submitted by the writer. If specified, must be between 2 and 6.
     * @var
     */
    private $rating;

    /**
     * The Stripe token used to pay for this order.
     * @var
     */
    private $stripeToken;

    /**
     * The cost for this order.
     * @var
     */
    private $price;



    /**
     * @return int
     */
    public function getProjectid()
    {
        return $this->projectid;
    }

    /**
     * @param int $projectid
     * @return Order
     */
    public function setProjectid($projectid)
    {
        $this->projectid = $projectid;
        return $this;
    }

    /**
     * @return int
     */
    public function getHourstocomplete()
    {
        return $this->hourstocomplete;
    }

    /**
     * @param int $hourstocomplete
     * @return Order
     */
    public function setHourstocomplete($hourstocomplete)
    {
        $this->hourstocomplete = $hourstocomplete;
        return $this;
    }

    /**
     * @return int
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * @param int $writer
     * @return Order
     */
    public function setWriter($writer)
    {
        $this->writer = $writer;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinwords()
    {
        return $this->minwords;
    }

    /**
     * @param int $minwords
     * @return Order
     */
    public function setMinwords($minwords)
    {
        $this->minwords = $minwords;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxwords()
    {
        return $this->maxwords;
    }

    /**
     * @param int $maxwords
     * @return Order
     */
    public function setMaxwords($maxwords)
    {
        $this->maxwords = $maxwords;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @param string $title
     * @return Order
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * @param string $instructions
     * @return Order
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;
        return $this;
    }

    /**
     * @return int
     */
    public function getCategoryid()
    {
        return $this->categoryid;
    }

    /**
     * @param int $categoryid
     * @return Order
     */
    public function setCategoryid($categoryid)
    {
        $this->categoryid = $categoryid;
        return $this;
    }

    /**
     * @return int
     */
    public function getAssetid()
    {
        return $this->assetid;
    }

    /**
     * @param int $assetid
     * @return Order
     */
    public function setAssetid($assetid)
    {
        $this->assetid = $assetid;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpertiseid()
    {
        return $this->expertiseid;
    }

    /**
     * @param int $expertiseid
     * @return Order
     */
    public function setExpertiseid($expertiseid)
    {
        $this->expertiseid = $expertiseid;
        return $this;
    }

    /**
     * @return int
     */
    public function getHourstoexpire()
    {
        return $this->hourstoexpire;
    }

    /**
     * @param int $hourstoexpire
     * @return Order
     */
    public function setHourstoexpire($hourstoexpire)
    {
        $this->hourstoexpire = $hourstoexpire;
        return $this;
    }

    /**
     * @return int
     */
    public function getHourstoapprovejon()
    {
        return $this->hourstoapprovejon;
    }

    /**
     * @param int $hourstoapprovejon
     * @return Order
     */
    public function setHourstoapprovejon($hourstoapprovejon)
    {
        $this->hourstoapprovejon = $hourstoapprovejon;
        return $this;
    }

    /**
     * @return int
     */
    public function getHourstorevise()
    {
        return $this->hourstorevise;
    }

    /**
     * @param int $hourstorevise
     * @return Order
     */
    public function setHourstorevise($hourstorevise)
    {
        $this->hourstorevise = $hourstorevise;
        return $this;
    }

    /**
     * @return int
     */
    public function getPaidreview()
    {
        return $this->paidreview;
    }

    /**
     * @param int $paidreview
     * @return Order
     */
    public function setPaidreview($paidreview)
    {
        $this->paidreview = $paidreview;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxhours()
    {
        return $this->maxhours;
    }

    /**
     * @param int $maxhours
     * @return Order
     */
    public function setMaxhours($maxhours)
    {
        $this->maxhours = $maxhours;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @param string $required
     * @return Order
     */
    public function setRequired($required)
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @return string
     */
    public function getOptional()
    {
        return $this->optional;
    }

    /**
     * @param string $optional
     * @return Order
     */
    public function setOptional($optional)
    {
        $this->optional = $optional;
        return $this;
    }

    /**
     * @return string
     */
    public function getSeo()
    {
        return $this->seo;
    }

    /**
     * @param string $seo
     * @return Order
     */
    public function setSeo($seo)
    {
        $this->seo = $seo;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpecial()
    {
        return $this->special;
    }

    /**
     * @param string $special
     * @return Order
     */
    public function setSpecial($special)
    {
        $this->special = $special;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getLovelist()
    {
        return $this->lovelist;
    }

    /**
     * @param boolean $lovelist
     * @return Order
     */
    public function setLovelist($lovelist)
    {
        $this->lovelist = $lovelist;
        return $this;
    }

    /**
     * @return int
     */
    public function getTargetwriter()
    {
        return $this->targetwriter;
    }

    /**
     * @param int $targetwriter
     * @return Order
     */
    public function setTargetwriter($targetwriter)
    {
        $this->targetwriter = $targetwriter;
        return $this;
    }

    /**
     * @return boolean allowhtml
     */
    public function getAllowhtml()
    {
        return $this->allowhtml;
    }

    /**
     * @param boolean $allowhtml
     * @return Order
     */
    public function setAllowhtml($allowhtml)
    {
        $this->allowhtml = $allowhtml;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return float
     */
    public function getBonus()
    {
        return $this->bonus;
    }

    /**
     * @param float $bonus
     */
    public function setBonus($bonus)
    {
        $this->bonus = $bonus;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return string
     */
    public function getStripeToken()
    {
        return $this->stripeToken;
    }

    /**
     * @param string $stripeToken
     */
    public function setStripeToken($stripeToken)
    {
        $this->stripeToken = $stripeToken;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }




    /**
     * Returns all the properties and values stored in an instance of the Order class.
     * @return array
     */
    public function toArray() {
        return array (
            "projectid" => $this->projectid,
            "hourstocomplete" => $this->hourstocomplete,
            "writer" => $this->writer,
            "minwords" => $this->minwords,
            "maxwords" => $this->maxwords,
            "status" => $this->status,
            "title" => $this->title,
            "text" => $this->text,
            "instructions" => $this->instructions,
            "categoryid" => $this->categoryid,
            "assetid" => $this->assetid,
            "expertiseid" => $this->expertiseid,
            "hourstoexpire" => $this->hourstoexpire,
            "hourstoapprovejon" => $this->hourstoapprovejon,
            "hourstorevise" => $this->hourstorevise,
            "paidreview" => $this->paidreview,
            "maxhours" => $this->maxhours,
            "required" => $this->required,
            "optional" => $this->optional,
            "seo" => $this->seo,
            "special" => $this->special,
            "lovelist" => $this->lovelist,
            "targetwriter" => $this->targetwriter,
            "allowhtml" => $this->allowhtml,
            "notes" => $this->notes,
            "bonus" => $this->bonus,
            "rating" => $this->rating,
            "stripeToken" => $this->stripeToken,
            "price" => $this->price,
        );
    }
}