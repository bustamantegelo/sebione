<?php

namespace App\Http\Controllers\Api\v1;

use App\Core\BaseTransformer;
use App\Repositories\UsersRepository;

/**
 * UsersApiController
 * @package App\Http\Controllers\Api\v1
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   09/03/2021
 * @version 1.0
 */
class UsersApiController
{
    /**
     * Users repository
     *
     * @var UsersRepository
     */
    protected $oUsersRepository;

    /**
     * UsersApiController constructor.
     *
     * @param UsersRepository $oUsersRepository
     */
    public function __construct(UsersRepository $oUsersRepository)
    {
        $this->oUsersRepository = $oUsersRepository;
    }

    /**
     * Display a listing of the users
     *
     * @return array
     */
    public function index()
    {
        try {
            $aUsers = $this->oUsersRepository->getAllUsers();

            return BaseTransformer::transformResponse($aUsers);
        } catch (\Throwable $oException) {
            return BaseTransformer::transformErrorResponse($oException->getCode(), $oException->getMessage(), 'index', 'UsersApiController');
        }
    }
}
