<?php

class DocumentController extends Controller
{
    public function formAction()
    {
        $document = new Document();
        $form = $this->container->get('form.factory')->create(new DocumentType(), $document);
        $request = $this->container->get('request');
        
        if ($request->getMethod() == 'POST') {
            if ($form->isValid()) {
                $document->processFile();
            }
        }
    }
}