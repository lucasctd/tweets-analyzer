source("shared/database.r")

getSentiment <- function(pre_candidato_id){
  
  query <- paste("SELECT score, magnitude FROM V_SENTIMENTOS WHERE precandidato_id = ", pre_candidato_id)
  
  res <- dbSendQuery(con, query)
  
  return(as.data.frame(fetch(res, -1)))
}