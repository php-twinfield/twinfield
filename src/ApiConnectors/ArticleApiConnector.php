<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Article;
use PhpTwinfield\DomDocuments\ArticlesDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\ArticleMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use Webmozart\Assert\Assert;

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
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Article|bool The requested article or false if it can't be found.
     * @throws Exception
     */
    public function get(string $code, Office $office): Article
    {
        // Make a request to read a single Article. Set the required values
        $request_article = new Request\Read\Article();
        $request_article
            ->setOffice($office->getCode())
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_article);

        return ArticleMapper::map($response);
    }

    /**
     * Sends an Article instance to Twinfield to update or add.
     *
     * @param Article $article
     * @return Article
     * @throws Exception
     */
    public function send(Article $article): Article
    {
        $articleResponses = $this->sendAll([$article]);

        Assert::count($articleResponses, 1);

        foreach ($articleResponses as $articleResponse) {
            return $articleResponse->unwrap();
        }
    }

    /**
     * @param Article[] $articles
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $articles): MappedResponseCollection
    {
        Assert::allIsInstanceOf($articles, Article::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($articles) as $chunk) {

            $articlesDocument = new ArticlesDocument();

            foreach ($chunk as $article) {
                $articlesDocument->addArticle($article);
            }

            $responses[] = $this->sendXmlDocument($articlesDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "article", function(Response $response): Article {
            return ArticleMapper::map($response);
        });
    }
}
