<?php
require_once 'config/bootstrap.php';

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
	protected $visitor;
	protected $epository;
		/**
		 * Initializes context.
		 *
		 * Every scenario gets its own context instance.
		 * You can also pass arbitrary arguments to the
		 * context constructor through behat.yml.
		 */
		public function __construct()
		{
		}

	/**
	 * @Given I fill a visitor email, first name, last name and groups with:
	 */
	public function iFillAVisitorEmailFirstNameLastNameAndGroupsWith(TableNode $table)
	{
		$data = $table->getHash()[0];
		$this->visitor = new Visitor($data);
	}

	/**
	 * @When I create a Visitor
	 */
	public function iCreateAVisitor()
	{
		$this->repository = new VisitorsRepository();
		$this->repository->create($this->visitor);
	}

	/**
	 * @Then a new visitor shold be added
	 */
	public function aNewVisitorSholdBeAdded()
	{
		if (!$this->visitor->id())
		throw new Exception('Visitor not has been created');
	}
}
