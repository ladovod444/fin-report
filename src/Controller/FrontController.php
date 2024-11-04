<?php

namespace User\FinReport\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use User\FinReport\Model\FinReports;

class FrontController
{
    private FinReports $model;

    /**
     * @param \User\FinReport\Model\FinReports $model
     *
     * @return $this
     */
    public function setModel(FinReports $model): static
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function index(): string {
        // Получаем данные пользователей имеющих транзакции
        $users = $this->model->getUserData();

        $twig = new Environment(new FilesystemLoader('../templates'));
        return $twig->
        render('index.html.twig', [
          'users' => $users,
        ]);
    }
}