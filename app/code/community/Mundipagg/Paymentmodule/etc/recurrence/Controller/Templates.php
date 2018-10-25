<?php

namespace Mundipagg\Recurrence\Controller;

use Mundipagg\Recurrence\Factories\TemplateRootFactory;
use Mundipagg\Recurrence\Repositories\Decorators\MagentoPlatformDatabaseDecorator;
use Mundipagg\Recurrence\Repositories\TemplateRepository;

class Templates extends Recurrence
{
    public function __call($name, array $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        }

        return $this->index();
    }

    public function index()
    {

    }

    protected function saveTemplate($postData)
    {
        $templateRootFactory = new TemplateRootFactory();
        if (!$this->validatePostData($postData)) {
            return $this->handleFormError();
        }

        try {
            $templateRoot = $templateRootFactory->createFromPostData($postData);

            $decorator = new MagentoPlatformDatabaseDecorator($this->platform);

            $templateRepository = new TemplateRepository($decorator);

            if (isset($postData['template-id'])) {
                $templateRoot->getTemplate()->setId($postData['template-id']);

                if (isset($this->openCart->request->get['updateChildren'])) {
                    $this->updateTemplate(
                        $this->openCart->request->get['updateChildren'],
                        $templateRoot
                    );
                }
            }

            $templateRepository->save($templateRoot);
        }catch(Exception $e) {
            $e->getMessage();
            throw $e;
        }

        /**
         * @todo redirect
         */
    }

    protected function updateTemplate($updateChildren, $templateRoot)
    {
        if (filter_var($updateChildren, FILTER_VALIDATE_BOOLEAN)) {
            return $this->updateChildrenProduct($templateRoot);
        }
        return $this->removeDependency($templateRoot);
    }

    protected function updateChildrenProduct($templateRoot)
    {
        if ($templateRoot->getTemplate()->isSingle()) {
            return;
        }

        $recurrencyProductRepository = new RecurrencyProductRepository(new OpencartPlatformDatabaseDecorator($this->openCart->db));
        $childProducts = $recurrencyProductRepository->getAllWithTemplateId(
            $templateRoot->getTemplate()->getId(),
            0,
            false
        );

        foreach ($childProducts as $recurrencyProduct) {

            $recurrencyProduct->setTemplate($templateRoot);
            $recurrencyProductRepository->save($recurrencyProduct);

            $planApi = new PlanApi($this->openCart);
            $planApi->save($recurrencyProduct);

        }
    }

    protected function removeDependency($templateRoot)
    {
        if ($templateRoot->getTemplate()->isSingle()) {
            return;
        }

        $recurrencyProductRepository = new RecurrencyProductRepository(new OpencartPlatformDatabaseDecorator($this->openCart->db));
        $recurrencyProductRepository->removeTemplateDependency($templateRoot);
    }

    protected function handleFormError()
    {
        $this->setBaseCreationFormData($this->openCart->error);
        $this->render('templates/create');
    }

    protected function validatePostData($postData)
    {
        $errors = [];
        try {
            //creating a templateRoot from json_data just to validate the input.
            (new TemplateRootFactory)->createFromPostData($postData);
        } catch (\Exception $exception) {
            $errors['recurrency_plan_input_error'] = $exception->getMessage();
        }

        if (count($errors)) {
            /**
             * @todo Handle errors
             */
            return false;
        };

        return true;
    }

    protected function setBaseCreationFormData($errors = [])
    {
        /**
         * @todo Handle this
         */
    }
}