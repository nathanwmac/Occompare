<?php

namespace App\Http\Controllers;

use App\Contracts\OccupationParser;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OccupationsController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $occparser;

    public function __construct(OccupationParser $parser)
    {
        $this->occparser = $parser;
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
                $return_scopes[] = 'skills';
                $occupation_1_skill_keys = array_keys($occupation_1_skills);
                $occupation_2_skill_keys = array_keys($occupation_2_skills);
                $skill_keys = array_unique(array_merge($occupation_1_skill_keys, $occupation_2_skill_keys));

                $total_skill_score = 0;
                foreach($skill_keys as $skill_key){
                    if(isset($occupation_1_skills[$skill_key]) && isset($occupation_2_skills[$skill_key])){
                        // Skill is shared by both occupations so calculate a score from 0-100 based on the difference of it's importance
                        $skill_score = 100 - abs($occupation_1_skills[$skill_key]['value'] - $occupation_2_skills[$skill_key]['value']);
                        $total_skill_score += $skill_score;
                        $skills[] = [
                            'label' => $occupation_1_skills[$skill_key]['label'],
                            'values' => [
                                $occupation_1_skills[$skill_key]['value'],
                                $occupation_2_skills[$skill_key]['value'],
                            ],
                            'score' => $skill_score,
                            'description' => $occupation_1_skills[$skill_key]['description']
                        ];
                    }else{
                        // Skill only applicable to one occupation so assign a score of 0
                        $skills[] = [
                            'label' => isset($occupation_1_skills[$skill_key]['label']) ? $occupation_1_skills[$skill_key]['label'] : $occupation_2_skills[$skill_key]['label'],
                            'values' => [
                                isset($occupation_1_skills[$skill_key]['value']) ? $occupation_1_skills[$skill_key]['value'] : 0,
                                isset($occupation_2_skills[$skill_key]['value']) ? $occupation_2_skills[$skill_key]['value'] : 0,
                            ],
                            'score' => 0,
                            'description' => isset($occupation_1_skills[$skill_key]['description']) ? $occupation_1_skills[$skill_key]['label'] : $occupation_2_skills[$skill_key]['description'],
                        ];
                    }
                }

                $skills_similarity = round($total_skill_score / count($skill_keys));
            }
        }

        if(in_array('knowledge', $request->get('scopes'))){

            $this->occparser->setScope('knowledge');
            $occupation_1_skills = $this->occparser->get($request->get('occupation_1'));
            $occupation_2_skills = $this->occparser->get($request->get('occupation_2'));

            if(count($occupation_1_skills) && count($occupation_2_skills)){
                $return_scopes[] = 'knowledge';
                $occupation_1_skill_keys = array_keys($occupation_1_skills);
                $occupation_2_skill_keys = array_keys($occupation_2_skills);
                $skill_keys = array_unique(array_merge($occupation_1_skill_keys, $occupation_2_skill_keys));

                $total_skill_score = 0;
                foreach($skill_keys as $skill_key){
                    if(isset($occupation_1_skills[$skill_key]) && isset($occupation_2_skills[$skill_key])){
                        // Skill is shared by both occupations so calculate a score from 0-100 based on the difference of it's importance
                        $skill_score = 100 - abs($occupation_1_skills[$skill_key]['value'] - $occupation_2_skills[$skill_key]['value']);
                        $total_skill_score += $skill_score;
                        $knowledge[] = [
                            'label' => $occupation_1_skills[$skill_key]['label'],
                            'values' => [
                                $occupation_1_skills[$skill_key]['value'],
                                $occupation_2_skills[$skill_key]['value'],
                            ],
                            'score' => $skill_score,
                            'description' => $occupation_1_skills[$skill_key]['description']
                        ];
                    }else{
                        // Skill only applicable to one occupation so assign a score of 0
                        $knowledge[] = [
                            'label' => isset($occupation_1_skills[$skill_key]['label']) ? $occupation_1_skills[$skill_key]['label'] : $occupation_2_skills[$skill_key]['label'],
                            'values' => [
                                isset($occupation_1_skills[$skill_key]['value']) ? $occupation_1_skills[$skill_key]['value'] : 0,
                                isset($occupation_2_skills[$skill_key]['value']) ? $occupation_2_skills[$skill_key]['value'] : 0,
                            ],
                            'score' => 0,
                            'description' => isset($occupation_1_skills[$skill_key]['description']) ? $occupation_1_skills[$skill_key]['label'] : $occupation_2_skills[$skill_key]['description'],
                        ];
                    }
                }

                $knowledge_similarity = round($total_skill_score / count($skill_keys));
            }
        }

        if(in_array('abilities', $request->get('scopes'))){

            $this->occparser->setScope('abilities');
            $occupation_1_skills = $this->occparser->get($request->get('occupation_1'));
            $occupation_2_skills = $this->occparser->get($request->get('occupation_2'));

            if(count($occupation_1_skills) && count($occupation_2_skills)){
                $return_scopes[] = 'abilities';
                $occupation_1_skill_keys = array_keys($occupation_1_skills);
                $occupation_2_skill_keys = array_keys($occupation_2_skills);
                $skill_keys = array_unique(array_merge($occupation_1_skill_keys, $occupation_2_skill_keys));

                $total_skill_score = 0;
                foreach($skill_keys as $skill_key){
                    if(isset($occupation_1_skills[$skill_key]) && isset($occupation_2_skills[$skill_key])){
                        // Skill is shared by both occupations so calculate a score from 0-100 based on the difference of it's importance
                        $skill_score = 100 - abs($occupation_1_skills[$skill_key]['value'] - $occupation_2_skills[$skill_key]['value']);
                        $total_skill_score += $skill_score;
                        $abilities[] = [
                            'label' => $occupation_1_skills[$skill_key]['label'],
                            'values' => [
                                $occupation_1_skills[$skill_key]['value'],
                                $occupation_2_skills[$skill_key]['value'],
                            ],
                            'score' => $skill_score,
                            'description' => $occupation_1_skills[$skill_key]['description']
                        ];
                    }else{
                        // Skill only applicable to one occupation so assign a score of 0
                        $abilities[] = [
                            'label' => isset($occupation_1_skills[$skill_key]['label']) ? $occupation_1_skills[$skill_key]['label'] : $occupation_2_skills[$skill_key]['label'],
                            'values' => [
                                isset($occupation_1_skills[$skill_key]['value']) ? $occupation_1_skills[$skill_key]['value'] : 0,
                                isset($occupation_2_skills[$skill_key]['value']) ? $occupation_2_skills[$skill_key]['value'] : 0,
                            ],
                            'score' => 0,
                            'description' => isset($occupation_1_skills[$skill_key]['description']) ? $occupation_1_skills[$skill_key]['label'] : $occupation_2_skills[$skill_key]['description'],
                        ];
                    }
                }

                $abilities_similarity = round($total_skill_score / count($skill_keys));
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
