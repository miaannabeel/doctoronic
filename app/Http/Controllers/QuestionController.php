<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::all();

        return $questions;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePre(Request $request)
    {
        $attributes = $request->all();

        $preQuestion = Question::whereNotNull('id')->orderByDesc('created_at')->first();

        if (isset($preQuestion) && isset($preQuestion->parent_id)) {
            $parentId = ($preQuestion->parent_id) + 1;
        } else {
            $parentId = 0;
        }

        $question = new Question();

        $question->question = $attributes['question'];
        $question->parent_id = $parentId;
        $question->save();

        foreach ($attributes['answers'] as $value) {
            $answer = new Answer();
            $answer->question_id = $question->id;
            $answer->answer = $value;
            $answer->save();
        }

        return $question;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->all();

        $question = new Question();

        if (isset($attributes['question']) && ($attributes['question'])) {
            $question->question = $attributes['question'];
            $question->category_id = $attributes['category_id'];
            $question->parent_id = 0;
            $question->save();
            $parentId = $question->id;

            foreach ($attributes['answers'] as $value) {
                $subQuestion = new Question();
                $subQuestion->category_id = $attributes['category_id'];
                $subQuestion->parent_id = $parentId;
                $subQuestion->question = $value;
                $subQuestion->save();
            }
        } elseif (isset($attributes['question_id']) && ($attributes['question_id'])) {

            foreach ($attributes['answers'] as $value) {
                $subQuestion = new Question();
                $subQuestion->parent_id = $attributes['question_id'];
                $subQuestion->category_id = $attributes['category_id'];
                $subQuestion->question = $value;
                $subQuestion->save();
            }
        }

        return $question;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Id  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Question::findOrFain($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        //
    }
}
