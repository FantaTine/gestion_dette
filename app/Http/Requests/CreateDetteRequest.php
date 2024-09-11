<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Article;

class CreateDetteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'montantDu' => 'required|numeric|min:0',
            'clientId' => 'required|exists:clients,id',
            'articles' => 'required|array|min:1',
            'articles.*.articleId' => 'required|exists:articles,id',
            'articles.*.qteVente' => 'required|integer|min:1',
            'articles.*.prixVente' => 'required|numeric|min:0',
            'paiement.montant' => 'nullable|numeric|min:0|max:'.$this->input('montantDu'),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->input('articles') as $article) {
                $stockArticle = Article::find($article['articleId']);
                if ($stockArticle->qteStock < $article['qteVente']) {
                    $validator->errors()->add("articles.{$article['articleId']}.qteVente", 'La quantité vendue dépasse le stock disponible.');
                }
            }
        });
    }
}
