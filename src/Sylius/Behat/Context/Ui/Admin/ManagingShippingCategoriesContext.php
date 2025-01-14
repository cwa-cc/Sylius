<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Admin\Crud\IndexPageInterface;
use Sylius\Behat\Page\Admin\ShippingCategory\CreatePageInterface;
use Sylius\Behat\Page\Admin\ShippingCategory\UpdatePageInterface;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;
use Webmozart\Assert\Assert;

class ManagingShippingCategoriesContext implements Context
{
    public function __construct(
        private IndexPageInterface $indexPage,
        private CreatePageInterface $createPage,
        private UpdatePageInterface $updatePage,
    ) {
    }

    /**
     * @When I want to create a new shipping category
     */
    public function iWantToCreateANewShippingCategory()
    {
        $this->createPage->open();
    }

    /**
     * @When /^I browse shipping categories$/
     */
    public function iWantToBrowseShippingCategories()
    {
        $this->indexPage->open();
    }

    /**
     * @Then I should see a single shipping category in the list
     * @Then I should see :numberOfShippingCategories shipping categories in the list
     */
    public function iShouldSeeShippingCategoriesInTheList(int $numberOfShippingCategories = 1): void
    {
        Assert::same($this->indexPage->countItems(), $numberOfShippingCategories);
    }

    /**
     * @When I specify its description as :shippingCategoryDescription
     */
    public function iSpecifyItsDescriptionAs($shippingCategoryDescription)
    {
        $this->createPage->specifyDescription($shippingCategoryDescription);
    }

    /**
     * @When I add it
     * @When I try to add it
     */
    public function iAddIt()
    {
        $this->createPage->create();
    }

    /**
     * @Then I should be notified that :element is required
     */
    public function iShouldBeNotifiedThatCodeIsRequired($element)
    {
        Assert::same(
            $this->updatePage->getValidationMessage($element),
            sprintf('Please enter shipping category %s.', $element),
        );
    }

    /**
     * @When I do not specify its code
     * @When I specify its code as :shippingCategoryCode
     */
    public function iSpecifyItsCodeAs($shippingCategoryCode = null)
    {
        $this->createPage->specifyCode($shippingCategoryCode ?? '');
    }

    /**
     * @When I name it :shippingCategoryName
     * @When I do not specify its name
     */
    public function iNameIt($shippingCategoryName = null)
    {
        $this->createPage->nameIt($shippingCategoryName ?? '');
    }

    /**
     * @Then I should see the shipping category :shippingCategoryName in the list
     */
    public function iShouldSeeTheShippingCategoryInTheList(string $shippingCategoryName): void
    {
        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $shippingCategoryName]));
    }

    /**
     * @Then /^the (shipping category "([^"]+)") should be in the registry$/
     * @Then /^the (shipping category "([^"]+)") should appear in the registry$/
     */
    public function theShippingCategoryShouldAppearInTheRegistry(ShippingCategoryInterface $shippingCategory)
    {
        $this->iWantToBrowseShippingCategories();

        Assert::true($this->indexPage->isSingleResourceOnPage(['code' => $shippingCategory->getCode()]));
    }

    /**
     * @When I delete shipping category :shippingCategoryName
     */
    public function iDeleteShippingCategory($shippingCategoryName)
    {
        $this->iWantToBrowseShippingCategories();
        $this->indexPage->deleteResourceOnPage(['name' => $shippingCategoryName]);
    }

    /**
     * @Then /^(this shipping category) should no longer exist in the registry$/
     */
    public function thisShippingCategoryShouldNoLongerExistInTheRegistry(ShippingCategoryInterface $shippingCategory)
    {
        Assert::false($this->indexPage->isSingleResourceOnPage(['code' => $shippingCategory->getCode()]));
    }

    /**
     * @Then shipping category with name :shippingCategoryName should not be added
     */
    public function shippingCategoryWithNameShouldNotBeAdded($shippingCategoryName)
    {
        Assert::false($this->indexPage->isSingleResourceOnPage(['name' => $shippingCategoryName]));
    }

    /**
     * @When /^I modify a (shipping category "([^"]+)")$/
     * @When /^I want to modify a (shipping category "([^"]+)")$/
     */
    public function iWantToModifyAShippingCategory(ShippingCategoryInterface $shippingCategory)
    {
        $this->updatePage->open(['id' => $shippingCategory->getId()]);
    }

    /**
     * @When I rename it to :name
     */
    public function iNameItIn($name)
    {
        $this->createPage->nameIt($name ?? '');
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @When I check (also) the :shippingCategoryName shipping category
     */
    public function iCheckTheShippingCategory(string $shippingCategoryName): void
    {
        $this->indexPage->checkResourceOnPage(['name' => $shippingCategoryName]);
    }

    /**
     * @When I delete them
     */
    public function iDeleteThem(): void
    {
        $this->indexPage->bulkDelete();
    }

    /**
     * @Then I should not be able to edit its code
     */
    public function iShouldNotBeAbleToEditItsCode(): void
    {
        Assert::true($this->updatePage->isCodeDisabled());
    }

    /**
     * @Then this shipping category name should be :shippingCategoryName
     */
    public function thisShippingCategoryNameShouldBe($shippingCategoryName)
    {
        Assert::true($this->updatePage->hasResourceValues(['name' => $shippingCategoryName]));
    }

    /**
     * @Then I should be notified that shipping category with this code already exists
     */
    public function iShouldBeNotifiedThatShippingCategoryWithThisCodeAlreadyExists()
    {
        Assert::same(
            $this->createPage->getValidationMessage('code'),
            'The shipping category with given code already exists.',
        );
    }

    /**
     * @Then there should still be only one shipping category with code :code
     */
    public function thereShouldStillBeOnlyOneShippingCategoryWith($code)
    {
        $this->iWantToBrowseShippingCategories();

        Assert::true($this->indexPage->isSingleResourceOnPage(['code' => $code]));
    }
}
