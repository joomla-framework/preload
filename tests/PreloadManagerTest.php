<?php

/**
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Preload\Tests;

use Fig\Link\Link;
use Joomla\Preload\PreloadManager;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Symfony\Component\WebLink\HttpHeaderSerializer;

/**
 * Test class for \Joomla\Preload\PreloadManager
 */
class PreloadManagerTest extends TestCase
{
    /**
     * @var  PreloadManager
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return  void
     */
    protected function setUp(): void
    {
        $this->object = new PreloadManager();
    }

    /**
     * @testdox  A Link header for the specified relations is added
     */
    public function testLink()
    {
        $this->object->link('/foo.css', 'preload prefetch', ['as' => 'style', 'crossorigin' => true]);

        $link = (new Link('preload prefetch', '/foo.css'))->withAttribute('as', 'style')->withAttribute('crossorigin', true);

        $this->assertEquals([$link], array_values($this->object->getLinkProvider()->getLinks()));
    }

    #[TestDox('A URI cannot close the angle brackets of its link and inject further links')]
    public function testLinkEncodesCharactersNotAllowedInUri()
    {
        $this->object->preload('/a.css>; rel=stylesheet,</evil.js', ['as' => 'style']);
        $this->object->preload('/my file "1".css');

        $links = array_values($this->object->getLinkProvider()->getLinks());

        $this->assertSame('/a.css%3E;%20rel=stylesheet,%3C/evil.js', $links[0]->getHref());
        $this->assertSame('/my%20file%20%221%22.css', $links[1]->getHref());
        $this->assertSame(
            '</a.css%3E;%20rel=stylesheet,%3C/evil.js>; rel="preload"; as="style",</my%20file%20%221%22.css>; rel="preload"',
            (new HttpHeaderSerializer())->serialize($links)
        );
    }

    #[TestDox('Valid URIs, including percent-encoded and templated ones, are not changed')]
    public function testLinkKeepsValidUris()
    {
        $uri = 'https://user@example.com:8443/a-b_c.d~e/f%20g.css?x=1&y[]=2;z=(3)*!$\'+,#frag';

        $this->object->preload($uri);
        $this->object->prefetch('/search{?q}');

        $links = array_values($this->object->getLinkProvider()->getLinks());

        $this->assertSame($uri, $links[0]->getHref());
        $this->assertSame('/search{?q}', $links[1]->getHref());
    }

    #[TestDox('A URI with control characters is rejected')]
    #[DataProvider('controlCharacterProvider')]
    public function testLinkRejectsControlCharactersInUri(string $uri)
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->object->preload($uri);
    }

    public static function controlCharacterProvider(): array
    {
        return [
            'CRLF'  => ["/evil.js\r\nSet-Cookie: x=1"],
            'LF'    => ["/evil.js\nSet-Cookie: x=1"],
            'tab'   => ["/a\t.css"],
            'NUL'   => ["/a\0.css"],
            'DEL'   => ["/a\x7F.css"],
        ];
    }

    #[TestDox('An invalid relation type is rejected')]
    #[DataProvider('invalidRelProvider')]
    public function testLinkRejectsInvalidRel(string $rel)
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->object->link('/foo.css', $rel);
    }

    public static function invalidRelProvider(): array
    {
        return [
            'empty'           => [''],
            'only spaces'     => ['  '],
            'quote'           => ['preload", </evil.js>; rel="stylesheet'],
            'semicolon'       => ['preload;foo'],
            'comma'           => ['preload,stylesheet'],
            'CRLF'            => ["preload\r\nSet-Cookie: x=1"],
            'tab separator'   => ["preload\tprefetch"],
            'leading digit'   => ['1preload'],
            'relative URI'    => ['/rel/foo'],
        ];
    }

    #[TestDox('Registered and extension relation types are accepted')]
    #[DataProvider('validRelProvider')]
    public function testLinkAcceptsValidRel(string $rel)
    {
        $this->object->link('/foo.css', $rel);

        $this->assertEquals([new Link($rel, '/foo.css')], array_values($this->object->getLinkProvider()->getLinks()));
    }

    public static function validRelProvider(): array
    {
        return [
            'registered'      => ['dns-prefetch'],
            'upper case'      => ['Preload'],
            'several'         => ['preload prefetch'],
            'extension'       => ['https://example.com/rels/foo'],
            'mixed'           => ['preload https://example.com/rels/foo'],
        ];
    }

    /**
     * @testdox  A Link header for the preload relation is added
     */
    public function testPreload()
    {
        $this->object->preload('/foo.css', ['as' => 'style', 'crossorigin' => true]);

        $link = (new Link('preload', '/foo.css'))->withAttribute('as', 'style')->withAttribute('crossorigin', true);

        $this->assertEquals([$link], array_values($this->object->getLinkProvider()->getLinks()));
    }

    /**
     * @testdox  A Link header for the dns-prefetch relation is added
     */
    public function testDnsPrefetch()
    {
        $this->object->dnsPrefetch('/foo.css', ['as' => 'style', 'crossorigin' => true]);

        $link = (new Link('dns-prefetch', '/foo.css'))->withAttribute('as', 'style')->withAttribute('crossorigin', true);

        $this->assertEquals([$link], array_values($this->object->getLinkProvider()->getLinks()));
    }

    /**
     * @testdox  A Link header for the preconnect relation is added
     */
    public function testPreconnect()
    {
        $this->object->preconnect('/foo.css', ['as' => 'style', 'crossorigin' => true]);

        $link = (new Link('preconnect', '/foo.css'))->withAttribute('as', 'style')->withAttribute('crossorigin', true);

        $this->assertEquals([$link], array_values($this->object->getLinkProvider()->getLinks()));
    }

    /**
     * @testdox  A Link header for the prefetch relation is added
     */
    public function testPrefetch()
    {
        $this->object->prefetch('/foo.css', ['as' => 'style', 'crossorigin' => true]);

        $link = (new Link('prefetch', '/foo.css'))->withAttribute('as', 'style')->withAttribute('crossorigin', true);

        $this->assertEquals([$link], array_values($this->object->getLinkProvider()->getLinks()));
    }

    /**
     * @testdox  A Link header for the prerender relation is added
     */
    public function testPrerender()
    {
        $this->object->prerender('/foo.css', ['as' => 'style', 'crossorigin' => true]);

        $link = (new Link('prerender', '/foo.css'))->withAttribute('as', 'style')->withAttribute('crossorigin', true);

        $this->assertEquals([$link], array_values($this->object->getLinkProvider()->getLinks()));
    }
}
