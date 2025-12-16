<?php
$text = $block->text()->value();
?>

<?php if ($text): ?>
<div class="block-markdown" style="margin: 30px 0;">
    <div class="markdown-content" style="line-height: 1.8; color: #333;">
        <?= kirbytext($text) ?>
    </div>
</div>

<style>
/* Markdown Block Styles */
.markdown-content h1 {
    font-size: 2.2rem;
    font-weight: bold;
    margin: 30px 0 20px 0;
    line-height: 1.3;
    color: #222;
}

.markdown-content h2 {
    font-size: 1.8rem;
    font-weight: bold;
    margin: 25px 0 15px 0;
    line-height: 1.3;
    color: #222;
}

.markdown-content h3 {
    font-size: 1.4rem;
    font-weight: 600;
    margin: 20px 0 10px 0;
    line-height: 1.4;
    color: #333;
}

.markdown-content h4 {
    font-size: 1.2rem;
    font-weight: 600;
    margin: 15px 0 10px 0;
    line-height: 1.4;
    color: #333;
}

.markdown-content p {
    margin: 0 0 15px 0;
    line-height: 1.8;
}

.markdown-content a {
    color: #0066cc;
    text-decoration: none;
    border-bottom: 1px solid rgba(0, 102, 204, 0.3);
    transition: border-color 0.2s;
}

.markdown-content a:hover {
    border-bottom-color: #0066cc;
}

.markdown-content strong {
    font-weight: 600;
    color: #222;
}

.markdown-content em {
    font-style: italic;
}

.markdown-content code {
    background: #f5f5f5;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: 'Monaco', 'Courier New', monospace;
    font-size: 0.9em;
    color: #d63384;
}

.markdown-content pre {
    background: #f5f5f5;
    padding: 15px;
    border-radius: 6px;
    overflow-x: auto;
    margin: 20px 0;
    border-left: 3px solid #0066cc;
}

.markdown-content pre code {
    background: none;
    padding: 0;
    color: #333;
    font-size: 0.95em;
}

.markdown-content ul,
.markdown-content ol {
    margin: 15px 0;
    padding-left: 30px;
}

.markdown-content ul {
    list-style-type: disc;
}

.markdown-content ol {
    list-style-type: decimal;
}

.markdown-content li {
    margin: 8px 0;
    line-height: 1.6;
}

.markdown-content li > ul,
.markdown-content li > ol {
    margin: 8px 0;
}

.markdown-content blockquote {
    margin: 20px 0;
    padding: 15px 20px;
    border-left: 4px solid #ddd;
    background: #f9f9f9;
    font-style: italic;
    color: #555;
}

.markdown-content blockquote p:last-child {
    margin-bottom: 0;
}

.markdown-content hr {
    border: none;
    border-top: 2px solid #e0e0e0;
    margin: 30px 0;
}

.markdown-content img {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
    margin: 20px 0;
}

.markdown-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.markdown-content table th {
    background: #f5f5f5;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid #ddd;
}

.markdown-content table td {
    padding: 10px 12px;
    border-bottom: 1px solid #eee;
}

.markdown-content table tr:hover {
    background: #fafafa;
}

/* First heading in block has no top margin */
.markdown-content > *:first-child {
    margin-top: 0;
}

/* Last element has no bottom margin */
.markdown-content > *:last-child {
    margin-bottom: 0;
}
</style>
<?php endif ?>
