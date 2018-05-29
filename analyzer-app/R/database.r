library(RMySQL)

con <- dbConnect(MySQL(), user='homestead', password='secret', dbname='tweets', host='localhost', port = 33060)