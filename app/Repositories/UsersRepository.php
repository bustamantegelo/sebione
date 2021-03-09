<?php

namespace App\Repositories;

use App\Constants\AppConstants;
use App\Constants\UsersConstants;
use App\Core\BaseRepository;
use App\Models\Users;
use Illuminate\Http\Request;

/**
 * UsersRepository
 * @package App\Repositories
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   09/03/2021
 * @version 1.0
 */
class UsersRepository extends BaseRepository
{
    /**
     * Users model
     *
     * @var Users $oUsers
     */
    protected $oUsers;

    /**
     * Filters for users table
     *
     * @var array $aFilter
     */
    protected $aFilter = [
        UsersConstants::EMAIL,
        UsersConstants::PASSWORD
    ];

    /**
     * UsersRepository constructor.
     *
     * @param Request $oRequest
     * @param Users $oUsers
     */
    public function __construct(Request $oRequest, Users $oUsers)
    {
        $this->oRequest = $oRequest;
        $this->oUsers = $oUsers;
    }

    /**
     * Get all users
     *
     * @return array
     */
    public function getAllUsers()
    {
        $aParam = $this->getFilter($this->aFilter);
        $aUsers = $this->oUsers
            ->where($aParam)
            ->get()
            ->toArray();
        $aCount = $this->getAllCountUsers();

        return array_merge($aCount, [AppConstants::USERS => $aUsers]);
    }

    /**
     * Count users
     *
     * @return array
     */
    public function getAllCountUsers()
    {
        $aParam = $this->getFilter($this->aFilter);
        $iCount = $this->oUsers
            ->where($aParam)
            ->count();

        return [
            AppConstants::COUNT => $iCount
        ];
    }
}
