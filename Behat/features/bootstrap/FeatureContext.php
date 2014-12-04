<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

class FeatureContext implements SnippetAcceptingContext
{
   private $output;

    public static function getAcceptedSnippetType()
    {
        return 'regex';
    }
    /**
     * @Given /^I am in a directory "([^"]*)"$/
     */
    public function iAmInADirectory($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        chdir($dir);
    }

    /** @Given /^I have a file named "([^"]*)"$/ */
    public function iHaveAFileNamed($file)
    {
        //touch($file);
	fopen($file,'w');
    }

    /** @When /^I run "([^"]*)"$/ */
    public function iRun($command)
    {
        exec($command, $output);
        $this->output = trim(implode("\n", $output));
    }

    /** @Then /^I should get:$/ */
    public function iShouldGet(PyStringNode $string)
    {
        if ((string) $string !== $this->output) {
            throw new Exception(
                "Actual output is:\n" . $this->output
            );
        }
    }

    private $shelf;
    private $basket;
    public function __construct()
    {
    $this->shelf = new Shelf();
    $this->basket = new Basket($this->shelf);
    }
    /**
    * @Given there is a :product, which costs £:price
    */
    public function thereIsAWhichCostsPs($product, $price)
    {
    $this->shelf->setProductPrice($product, floatval($price));
    }
    /**
    * @When I add the :product to the basket
    */
    public function iAddTheToTheBasket($product)
    {
    $this->basket->addProduct($product);
    }
    /**
    * @Then I should have :count product(s) in the basket
    */
    public function iShouldHaveProductInTheBasket($count)
    {
    PHPUnit_Framework_Assert::assertCount(intval($count), $this->basket);
    }
    /**
    * @Then the overall basket price should be £:price
    */
    public function theOverallBasketPriceShouldBePs($price)
    {
    PHPUnit_Framework_Assert::assertSame(floatval($price), $this->basket->getTotalPrice());
    }

}
