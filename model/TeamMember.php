 <!-- #teammember modal in module:  -->

<?php

namespace Modules\Crm\Entities;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $table    = 'team_members';
    protected $guarded  = ['id'];
    public $timestamps = false;

}
