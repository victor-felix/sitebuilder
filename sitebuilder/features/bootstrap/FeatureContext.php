<?php
require_once 'config/bootstrap.php';

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\validators\VisitorsPersistenceValidator;
/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
	protected $visitor;
	protected $visitorRepository;
	/**
	 * Initializes context.
	 *
	 * Every scenario gets its own context instance.
	 * You can also pass arbitrary arguments to the
	 * context constructor through behat.yml.
	 */
	public function __construct()
	{
		$this->visitorRepository = new VisitorsRepository();
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
		$validator = new VisitorsPersistenceValidator();
		$result = $validator->validate($this->visitor);
		if ($result->isValid()) {
			$this->visitorRepository->create($this->visitor);
		}
	}

	/**
	 * @Then a new visitor should be added
	 */
	public function aNewVisitorShouldBeAdded()
	{
		if (!$this->visitor->id())
			throw new Exception('Visitor not has been added');
	}

	/**
	 * @Given I fill a visitor email, first name, last name and groups with invalid values:
	 */
	public function iFillAVisitorEmailFirstNameLastNameAndGroupsWithInvalidValues(TableNode $table)
	{
		$data = $table->getHash()[0];
		$this->visitor = new Visitor($data);
	}

	/**
	 * @Then a new visitor should NOT be added
	 */
	public function aNewVisitorShouldNotBeAdded()
	{
		if ($this->visitor->id())
			throw new Exception('An invalid Visitor has been added');
	}

	/**
	 * @Given that there is a visitor
	 */
	public function thatThereIsAVisitor()
	{
		$visitors = $this->visitorRepository->all();
		if (empty($visitors))
			throw new Exception('No Visitor available');
		$this->visitor = $visitors[0];
	}

	/**
	 * @When I reset it's password
	 */
	public function iResetItSPassword()
	{
		$this->visitor->setRandomPassword();
	}

	/**
	 * @Then the visitor password should be invalidated
	 */
	public function theVisitorPasswordShouldBeInvalidated()
	{
		if ($this->visitor->isPasswordValid()) {
			throw new Exception('The Visitor generated password is valid');
		}
	}

	/**
	 * @When I remove this visitor
	 */
	public function iRemoveThisVisitor()
	{
		$this->visitorRepository->destroy($this->visitor);
	}

	/**
	 * @Then the visitor should not exist anymore
	 */
	public function theVisitorShouldNotExistAnymore()
	{
		try {
			$this->visitorRepository->find($this->visitor->id());
			throw new Exception('Visitor was not removed');
		} catch (\meumobi\sitebuilder\repositories\RecordNotFoundException $e) {
			//do nothing
		}
	}
}
