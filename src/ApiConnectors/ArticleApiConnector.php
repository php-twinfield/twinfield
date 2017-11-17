<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Article;
use PhpTwinfield\DomDocuments\ArticlesDocument;
use PhpTwinfield\Mappers\ArticleMapper;
use PhpTwinfield\Request as Request;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Articles.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class ArticleApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific Article based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param string $office Optional. If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Article|bool The requested article or false if it can't be found.
     */
    public function get(string $code, ?string $office = null)
    {
        // Attempts to process the login
        if ($this->getLogin()->process()) {
            // Get the secure service class
            $service = $this->createService();

            // No office passed, get the office from the Config
            if (!$office) {
                $office = $this->getConfig()->getOffice();
            }

            // Make a request to read a single Article. Set the required values
            $request_article = new Request\Read\Article();
            $request_article
                ->setOffice($office)
                ->setCode($code);

            // Send the Request document and set the response to this instance.
            $response = $service->send($request_article);
            $this->setResponse($response);

            // Return a mapped article if successful or false if not.
            if ($response->isSuccessful()) {
                return ArticleMapper::map($response);
            }
        }

        return false;
    }

    /**
     * Sends a \PhpTwinfield\Article\Article instance to Twinfield to update or add.
     *
     * If you want to map the response back into an Article use getResponse()->getResponseDocument()->asXML() into the
     * ArticleMapper::map() method.
     *
     * @param Article $article
     * @return bool
     */
    public function send(Article $article): bool
    {
        // Attempts the process login
        if ($this->getLogin()->process()) {
            // Gets the secure service
            $service = $this->createService();

            // Gets a new instance of ArticlesDocument and sets the $article
            $articlesDocument = new ArticlesDocument();
            $articlesDocument->addArticle($article);

            // Send the DOM document request and set the response
            $response = $service->send($articlesDocument);
            $this->setResponse($response);

            // Return a bool on status of response.
            return $response->isSuccessful();
        }

        return false;
    }
}
