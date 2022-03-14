<?php

namespace App\Http\Controllers;

use App\Contracts\OccupationParser;
use App\Contracts\OccupationComparator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OccupationsController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $occparser;
    private $occcomparator;

    public function __construct(OccupationParser $parser, OccupationComparator $comparator)
    {
        $this->occparser = $parser;
        $this->occcomparator = $comparator;
    }

    public function index()
    {
        return $this->occparser->list();
    }

    public function compare(Request $request)
    {
        $return_scopes = [];
        $skills = [];
        $knowledge = [];
        $abilities = [];
        $skills_similarity = 0;
        $knowledge_similarity = 0;
        $abilities_similarity = 0;

        if(in_array('skills', $request->get('scopes'))){

            $this->occparser->setScope('skills');
            $occupation_1_skills = $this->occparser->get($request->get('occupation_1'));
            $occupation_2_skills = $this->occparser->get($request->get('occupation_2'));

            if(count($occupation_1_skills) && count($occupation_2_skills)){
                
                $result = $this->occcomparator->compare('skills', $occupation_1_skills, $occupation_2_skills);
                if($result){
                    $return_scopes[] = 'skills';
                    $skills = $result['list'];
                    $skills_similarity = $result['similarity'];
                }
            }
        }

        if(in_array('knowledge', $request->get('scopes'))){

            $this->occparser->setScope('knowledge');
            $occupation_1_knowledge = $this->occparser->get($request->get('occupation_1'));
            $occupation_2_knowledge = $this->occparser->get($request->get('occupation_2'));

            if(count($occupation_1_knowledge) && count($occupation_2_knowledge)){

                $result = $this->occcomparator->compare('knowledge', $occupation_1_knowledge, $occupation_2_knowledge);
                if($result){
                    $return_scopes[] = 'knowledge';
                    $knowledge = $result['list'];
                    $knowledge_similarity = $result['similarity'];
                }
            }
        }

        if(in_array('abilities', $request->get('scopes'))){

            $this->occparser->setScope('abilities');
            $occupation_1_abilities = $this->occparser->get($request->get('occupation_1'));
            $occupation_2_abilities = $this->occparser->get($request->get('occupation_2'));

            if(count($occupation_1_abilities) && count($occupation_2_abilities)){

                $result = $this->occcomparator->compare('abilities', $occupation_1_abilities, $occupation_2_abilities);
                if($result){
                    $return_scopes[] = 'abilities';
                    $abilities = $result['list'];
                    $abilities_similarity = $result['similarity'];
                }
            }
        }

        $match = count($return_scopes) > 0 ? round(($skills_similarity + $knowledge_similarity + $abilities_similarity) / count($return_scopes)) : null;

        return [
            'skills' => $skills,
            'skills_similarity' => $skills_similarity,
            'knowledge' => $knowledge,
            'knowledge_similarity' => $knowledge_similarity,
            'abilities' => $abilities,
            'abilities_similarity' => $abilities_similarity,
            'scopes' => $return_scopes,
            'match' => $match,
        ];
    }
}
